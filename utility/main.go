package main

import (
	"context"
	"flag"
	"log"
	"os"
	"os/exec"
	"syscall"
	"time"
	"database/sql"
	_ "github.com/go-sql-driver/mysql"
	"fmt"
	"bytes"
	"io/ioutil"
	"io"
	"encoding/json"
	"strings"
)

var (
	timeLimit float64
	memoryLimit int64
	solutionId int
	problemId int
	userId int
	languageId int
	version bool
	config Config
	fileDir string
	sourceCode string
	db *sql.DB
	acm bool
	extension string
	compileCommand string
)

type Config struct {
	MySqlHost	string
	MySqlPort	string
	MySqlUser	string
	MySqlPassword   string
	MySqlDbName	string
}

const (
	Accepted = 1
	WrongAnswer = 2
	TimeLimitExceeded = 3
	PresentationError = 4
	CompilationError = 5
	MemoryLimitExceeded = 6
	RuntimeError = 7
	Compiling = 8
	Running = 9
	Waiting = 10
)

func init() {
	log.SetFlags(log.LstdFlags | log.Lshortfile)
	flag.IntVar(&solutionId, "solution-id", 0, "Solution id")
	flag.BoolVar(&acm, "acm", true, "Test by acm rules")
	flag.BoolVar(&version, "v", false, "Script version")
	flag.Parse()

	if solutionId == 0 {
		log.Fatal("Solution id should be set")
	}

	dir, err := os.Getwd()
	if err != nil {
		log.Fatal(err)
	}
	fileDir = dir

	file, err := os.Open(fileDir + "/config/config.json")
	if err != nil {
		log.Fatal(err)
	}
	decoder := json.NewDecoder(file)

	err = decoder.Decode(&config)
	if err != nil {
		log.Fatal("error: ", err)
	}
	db, err = sql.Open("mysql", fmt.Sprintf("%s:%s@tcp(%s:%s)/%s", config.MySqlUser, config.MySqlPassword, config.MySqlHost, config.MySqlPort, config.MySqlDbName))
	if err != nil {
		log.Fatal(err)
	}

	stmtOut, err := db.Prepare("SELECT problem_id, user_id, language_id, source_code FROM solution WHERE id = ?")
	if err != nil {
		log.Fatal(err)
	}
	defer stmtOut.Close()

	err = stmtOut.QueryRow(solutionId).Scan(&problemId, &userId, &languageId, &sourceCode)
	if err != nil {
		log.Fatal(err)
	}


	err = ioutil.WriteFile(fmt.Sprintf("%s/uploads/%d/%d%s", fileDir, userId, problemId, extension), []byte(sourceCode), 0777)
	if err != nil {
		log.Fatal(err)
	}

	stmtOut, err = db.Prepare("SELECT timeLimit, memoryLimit FROM problem WHERE id = ?")
	if err != nil {
		log.Fatal(err)
	}
	defer stmtOut.Close()

	err = stmtOut.QueryRow(problemId).Scan(&timeLimit, &memoryLimit)
	if err != nil {
		log.Fatal(err)
	}

	stmtOut, err = db.Prepare("SELECT command, extension FROM language WHERE id = ?")
	if err != nil {
		log.Fatal(err)
	}
	defer stmtOut.Close()
	err = stmtOut.QueryRow(languageId).Scan(&compileCommand, &extension)
	if err != nil {
		log.Fatal(err)
	}
}

func main() {
	if version {
		PrintVersionAndExit()
	}

	log.Println("Timelimit: ", timeLimit)
	log.Println("Memorylimit: ", memoryLimit)
	log.Println("problemId: ", problemId)

	ctx, cancel := context.WithTimeout(context.Background(), time.Duration(timeLimit)*time.Millisecond)
	defer cancel()

	stmtUpd, err := db.Prepare("UPDATE solution set status_id=? where id=?")
	if err != nil {
		log.Fatal(err)
	}
	defer stmtUpd.Close()
	_, err = stmtUpd.Exec(Compiling, solutionId)
	if err != nil {
		log.Fatal(err)
	}
	stderr, exitCode := Compile()
	if exitCode != 0 {
		stmtUpd, err = db.Prepare("UPDATE solution set status_id = ?, compiler_message = ? where id = ?")
		if err != nil {
			log.Fatal(err)
		}
		_, err := stmtUpd.Exec(CompilationError, stderr, solutionId)
		if err != nil {
			log.Fatal(err)
		}
		os.Exit(CompilationError)
	} else {
		stmtUpd, err = db.Prepare("UPDATE solution set status_id = ? where id = ?")
		if err != nil {
			log.Fatal(err)
		}
		_, err := stmtUpd.Exec(Running, solutionId)
		if err != nil {
			log.Fatal(err)
		}
	}

	stmtIns, err := db.Prepare("INSERT INTO test_result VALUES(?, ?, ?, ?, ?, ?)")
	if err != nil {
		log.Fatal(err)
	}
	defer stmtIns.Close()

	var out bytes.Buffer

	testCount, err := TestsCount()
	if err != nil {
		log.Fatal(err)
	}

	log.Println("TestCount: ", testCount)

	for i := 1; i <= testCount; i ++ {
		in, err := ioutil.ReadFile(fmt.Sprintf("tests/%d/%d.in", problemId, i))

		if err != nil {
			log.Fatal(err)
		}

		log.Println("Started test number: ", i)
		cmd := exec.CommandContext(ctx, fmt.Sprintf("uploads/%d/%d", userId, problemId))
		//cmd.SysProcAttr = &syscall.SysProcAttr{}
		//cmd.SysProcAttr.Credential = &syscall.Credential{Uid: 1001, Gid: 1001}
		out.Reset()
		cmd.Stdout = &out
		cmd.Dir = fileDir
		result := 0
		var memoryUsage int64

		stdin, err := cmd.StdinPipe()
		_, err = io.WriteString(stdin, string(in))
		if err != nil {
			log.Fatal(err)
		}
		start := time.Now()
		e := cmd.Run()
		if err != nil {
			log.Fatal(err)
		}
		executedTime := time.Since(start).Seconds()
		//timelimit
		if executedTime > timeLimit {
			result = TimeLimitExceeded
		}

		log.Println("executed time: ", executedTime)
		if e != nil {
			result = RuntimeError //runtime error
			log.Println("result: ", e)
		} else {
			err = os.MkdirAll(fmt.Sprintf("outputs/%d/%d/", userId, problemId), 0777)
			if err != nil {
				log.Fatal(err)
			}
			err = ioutil.WriteFile(fmt.Sprintf("outputs/%d/%d/%d", userId, problemId, i), out.Bytes(), 0777)
			if err != nil {
				log.Fatal(err)
			}
			checkerCmd := exec.Command(fmt.Sprintf("checkers/checker_%d", problemId), fmt.Sprintf("tests/%d/%d.in", problemId, i), fmt.Sprintf("tests/%d/%d.out", problemId, i), fmt.Sprintf("outputs/%d/%d/%d", userId, problemId, i))
			if err := checkerCmd.Start(); err != nil {
				log.Fatalf("cmd.Start: %v", err)
			}

			if err := checkerCmd.Wait(); err != nil {
				if exitErr, ok := err.(*exec.ExitError); ok {
					if status, ok := exitErr.Sys().(syscall.WaitStatus); ok {
						switch status.ExitStatus() {
						case 1:
							result = WrongAnswer
							break
						case 2:
							result = PresentationError
							break
						case 3:
							result = PresentationError
							break
						default:
							result = PresentationError

						}
					}
				} else {
					result = 6 // my error
					log.Fatalf("cmd.Wait: %v", err)
				}
			} else {
				result = Accepted //accepted
			}

			memoryUsage = cmd.ProcessState.SysUsage().(*syscall.Rusage).Maxrss

			log.Println("Usage memory: ", memoryUsage, "Byte")
			//MemoryLimit
			if memoryUsage > memoryLimit {
				result = MemoryLimitExceeded
			}
			log.Println("Test result:", result)
		}

		_, err = stmtIns.Exec(nil, solutionId, i, result, executedTime, memoryUsage)

		if err != nil {
			log.Println("Error on inserting test result:", err)
			log.Fatal(err)
		}

		stmtUpd, err = db.Prepare("UPDATE solution set status_id = ?, time = CASE WHEN time < ? THEN ? ELSE time END, memory = CASE WHEN memory < ? THEN ? ELSE memory END, test = ? where id = ?")
		if err != nil {
			log.Fatal(err)
		}
		solutionStatus := Running //TODO if acm false we should change last displayed solution
		if acm && result != Accepted {
			solutionStatus = result
		}

		if i == testCount && result == Accepted {
			solutionStatus = Accepted
		}
		_, err = stmtUpd.Exec(solutionStatus, executedTime, executedTime, memoryUsage, memoryUsage, i, solutionId)
		if err != nil {
			log.Fatal(err)
		}

		if acm && result != Accepted {
			log.Println("Result status: ", result)
			os.Exit(result)
		}

		log.Println("_____________________________________")
	}
	log.Println("end")
	defer db.Close()
}

func TestsCount() (int, error) {
	count := 0
	files, err := ioutil.ReadDir(fmt.Sprintf("%s/tests/%d", fileDir, problemId))
	if err != nil {
		return count, err
	}

	for _, file := range files {
		if !file.IsDir() && strings.Contains(file.Name(), ".in") {
			count++
		}
	}

	return count, nil
}

func Compile() (stderr string, exitCode int) {
	// see more about runProcess here: https://stackoverflow.com/questions/10385551/get-exit-code-go/40770011#40770011
	command := strings.Split(fmt.Sprintf(compileCommand, problemId, problemId), " ")
	_, stderr, exitCode = RunCommand(command[0], command[1:]...)
	return
}

func RunCommand(name string, args ...string) (stdout string, stderr string, exitCode int) {
	var outbuf, errbuf bytes.Buffer
	cmd := exec.Command(name, args...)
	cmd.Dir = fmt.Sprintf("%s/uploads/%d", fileDir, userId)
	cmd.Stdout = &outbuf
	cmd.Stderr = &errbuf

	err := cmd.Run()
	stdout = outbuf.String()
	stderr = errbuf.String()

	if err != nil {
		if exitError, ok := err.(*exec.ExitError); ok {
			ws := exitError.Sys().(syscall.WaitStatus)
			exitCode = ws.ExitStatus()
		} else {
			if stderr == "" {
				stderr = err.Error()
			}
		}
	} else {
		ws := cmd.ProcessState.Sys().(syscall.WaitStatus)
		exitCode = ws.ExitStatus()
	}
	return
}

func PrintVersionAndExit() {
	fmt.Println("Version 0.0.1\nContact info: i.adilets@gmail.com")
	os.Exit(0)
}
