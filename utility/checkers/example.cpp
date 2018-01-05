#include "testlib.h"
#include <iostream>

int main(int argc, char * argv[])
{
    //ofstream cout("hehe.txt");
    setName("compare two integers");
    registerTestlibCmd(argc, argv);
	//quitf(_fail, "just test");
    int ja = ans.readInt();
    int pa = ouf.readInt();



    if (ja != pa)
        quitf(_wa, "expected %d, found %d", ja, pa);

    quitf(_ok, "answer is %d", ja);
}
