#1.0.3
* Resquest 中增加getData方法，返回request data
* 增加Logger工具TODO
    * logid cuid bot_name ctime
    * 响应时间
        * 打印总的耗时时间all_t:int (ms)
        * 打印自身耗时，all_t - 串行io等待时间(io_t)
    * 日志存储配置
        * 配置存储目录(wf, notice)
        * 切分方式

#1.0.5
* Response 中针对只返回resource，补command，确保不被中控干掉

#2.1.4
* 增加数据统计BotMonitor
