package main

import (
	"context"
	"flag"
	"log"
	"os"
	"os/exec"
	"path/filepath"
	"syscall"
	"time"
	"database/sql"
	_ "github.com/go-sql-driver/mysql"
	"fmt"
	"bytes"
	"io/ioutil"
	"io"
	"encoding/json"
)

var timelimit int
var filename string
var problemId int
var testCount int
var version bool
var userId int
var config Config

type Config struct {
	MySqlHost	string
	MySqlPort	string
	MySqlUser	string
	MySqlPassword   string
	MySqlDbName	string
}

func init() {
	flag.IntVar(&timelimit, "timelimit", 1000, "time to execute program (millisecond)")
	flag.StringVar(&filename, "filename", "a.out", "filename ")
	flag.IntVar(&problemId, "problem-id", 0, "Problem id")
	flag.IntVar(&testCount, "test-count", 0, "Tests count")
	flag.IntVar(&userId, "user-id", 0, "User id")
	flag.BoolVar(&version, "v", false, "Script version")
}

func main() {
	flag.Parse()

	if version {
		printVersionAndExit()
	}

	file, err1 := os.Open("config.json")
	if err1 != nil {
		log.Fatal(err1)
	}
	decoder := json.NewDecoder(file)

	err := decoder.Decode(&config)
	if err != nil {
		fmt.Println("error: ", err)
	}


	fmt.Println("\x1b[31;1mHello, World!\x1b[0m")

	log.Println("Timelimit: ", timelimit)
	log.Println("Filename: ", filename)
	log.Println("host: ", config.MySqlHost)
	log.Println("port: ", config.MySqlPort)
	log.Println("username: ", config.MySqlUser)
	log.Println("password: ", config.MySqlPassword)
	log.Println("dbname: ", config.MySqlDbName)
	log.Println("problemId: ", problemId)
	log.Println("testCount: ", testCount)

	dir, err := filepath.Abs(filepath.Dir(os.Args[0]))
	if err != nil {
		log.Fatal(err)
	}

	ctx, cancel := context.WithTimeout(context.Background(), time.Duration(timelimit)*time.Millisecond)
	defer cancel()


	db, err := sql.Open("mysql", fmt.Sprintf("%s:%s@tcp(%s:%s)/%s", config.MySqlUser, config.MySqlPassword, config.MySqlHost, config.MySqlPort, config.MySqlDbName))
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()


	stmtIns, err := db.Prepare("INSERT INTO test_result VALUES(?, ?, ?, ?, ?, ?, ? )")
	if err != nil {
		log.Fatal(err)
	}
	defer stmtIns.Close()

	for i := 1; i <= testCount; i ++ {
		var out bytes.Buffer
		in, err := ioutil.ReadFile(fmt.Sprintf("tests/%d/%d.in", problemId, i))

		if err != nil {
			log.Fatal(err)
		}

		log.Println("Started test number: ", i)
		cmd := exec.CommandContext(ctx, fmt.Sprintf("./%s", filename))
		cmd.SysProcAttr = &syscall.SysProcAttr{}
		cmd.SysProcAttr.Credential = &syscall.Credential{Uid:1001, Gid:1001}
		cmd.Stdout = &out
		cmd.Dir = dir
		result := 0
		var memoryUsage int64

		stdin, err := cmd.StdinPipe()
		_, err = io.WriteString(stdin, string(in))
		if err != nil {
			log.Fatal(err)
		}
		start := time.Now()
		e := cmd.Run()
		executedTime := time.Since(start)
		log.Println("executed time: ", executedTime.String())
		if e != nil {
			result = 1 //runtime error
			log.Println("result: ", e)
		} else {
			out.String()
			ioutil.WriteFile(fmt.Sprintf("outputs/output_%d", i), out.Bytes(), 0777)

			checkerCmd := exec.Command(fmt.Sprintf("testlib/checker_%d", problemId), fmt.Sprintf("tests/%d/%d.in", problemId, i), fmt.Sprintf("tests/%d/%d.out", problemId, i), fmt.Sprintf("outputs/output_%d", i))
			if err := checkerCmd.Start(); err != nil {
				log.Fatalf("cmd.Start: %v", err)
			}

			if err := checkerCmd.Wait(); err != nil {
				if exiterr, ok := err.(*exec.ExitError); ok {
					if status, ok := exiterr.Sys().(syscall.WaitStatus); ok {
						switch status.ExitStatus() {
							case 1:
								result = 2 //wrong answer
								break
							case 2:
								result = 3 //presentation error
								break
							case 3:
								result = 4 //fail exit
								break
							default:
								result = 5 //unknown error
							
						}
					}
				} else {
					result = 6 // my error
					log.Fatalf("cmd.Wait: %v", err)
				}
			} else {
				result = 0 //accepted
			}

			memoryUsage = cmd.ProcessState.SysUsage().(*syscall.Rusage).Maxrss
			log.Println("Usage memory: ", memoryUsage / 1024, "KB")
			log.Println("Test result:", result)
		}

		_, err = stmtIns.Exec(nil, problemId, i, result, memoryUsage, executedTime.String(), userId)

		if err != nil {
			log.Println("Error on inserting test result:", err)
			log.Fatal(err)
		}
		log.Println("_____________________________________")
	}
	log.Println("end")
}

func printVersionAndExit() {
	fmt.Println("Version 0.0.1\nContact info: i.adilets@gmail.com")
	os.Exit(0)
}
