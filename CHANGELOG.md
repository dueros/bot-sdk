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

#2.1.5
* Request增加新的接口

#2.1.6
* 增加autoCompleteSpeech字段, 默认为true

#2.1.7
* 增加注释

#2.1.8
* TTSTemplate 话术模板
* LinkAccountCard账号关联卡片

#2.1.9
* TTSTemplates 话术模板更新
* after search服务给出的打分 

#2.1.10
* 增加sample bot
* 支持AudioPlayerInfo
* 支持Hint指令
* 支持VideoPlayer指令及获取端VideoPlayer状态
* 支持获取端展现的Card状态
* 增加template模版展现

#2.1.11
* update README
* fix VideoPlayer.Play.setOffsetInMilliseconds

#2.1.12
* fix RenderTemplate __construct

#2.1.13
* 增加RenderAudioList
* 增加RenderVideoList
* 增加Charge指令
* 增加supportedInterfaces

#2.1.15
* 增加列表标签Tag
* 增加BodyTemplate6
* 增加ListTemplate3
* 增加ListTemplate4
* 增加RenderVideoPlayerInfo
* 增加RenderAudioPlayerInfo

#2.1.16
* 增加Display.PushStack指令
* 增加expectResponse
* 增加Record.RecordSpeech指令
* 增加Permission.AskForPermissionsConsent指令

#2.1.17
* 更新Permission
* directives指令排序方式
* 增加设备VideoPlayer分辨率
* 增加UI CONTROL支持

#2.1.18
* 增加权限api请求接口

#2.1.19
* 增加Buy指令

#2.1.20 
* 增加DPL相关API
