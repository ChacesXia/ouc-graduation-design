
### 总体架构 ###
改系统分为两大部分，core和web，分别对应判题模块和web模块两大功能。
core和web之间数据交换有两种方式：
1. 通过数据库，轮询。（true）
2. 通过wget实现的http请求。

两种方式的选择在判题端的配置文件/home/judge/etc/judge.conf中，HTTP_JUDGE=1则启用后者，默认为前者。


### judged模块 ###

1. judged 可以接受一个参数作为自己的主目录，默认是/home/judge/。

```bash
sudo judged /home/judge/local
```
不指定参数将自动以单进程运行;当指定的参数不为/home/judge时，就会有多个进程出现。
每个主目录可以有自己的etc/judge.conf 数据目录可以共享，runX目录需要独立。


2. judged调试模式

judged 接受参数指定目录的情况下，还可以再接受一个debug作为调试模式开关。
```bash
sudo judged /home/judge/local debug 
```
调试模式的judged将不会进入后台，并且将输出大量调试信息，其调用的judge_client也工作在debug模式。


### judge_client模块 ###

当发现新任务时产生judge_client进程。judge_client进程为实际判题程序，负责准备运行环境、数据，运行并监控目标程序的系统调用，采集运行指标，判断运行结果。

#### judge_client的调试模块:

judge_client [工作主目录] [调试]

```bash
judge_client  2001 5 /home/judge/demo debug
```

将在/home/judge/demo/run5目录中对2001号提交进行重判，并打开调试模式，输出大量调试信息，运行后不删除中间结果。
这个模式可以帮助调试题目数据，发现数据问题和了解提交RE的详细错误原因


### sim模块（未使用） ###

当配置为启用抄袭检查时，judge_client将调用sim，判断相似性结果，并写回数据库或web端。
sim为第三方应用程序，可进行语法分析判断文本相似度，通过检验的程序将由judge_client复制进题目数据的ac目录，成为新的参考样本。

## 后台安全机制 ##

ptrace工具


# 错误处理方案: #

#### 1. gcc: error trying to exec 'cc1': execvp: 没有该文件或目录的错误 ####

解决方法:

1. 注释改行:
[https://github.com/ChacesXia/ouc-graduation-design/blob/master/core_judge/judge_client.cc#L917](https://github.com/ChacesXia/ouc-graduation-design/blob/master/core_judge/judge_client.cc#L917)

2. 重新编译替代源程序

#### 2./bin/ld connot find -lstdc++  ####

解决方法:

```bash

sudo yum install -y libstdc++.x86_64
sudo yum install -y libstdc++-devel.x86_64
sudo yum install -y libstdc++-static.x86_64

```