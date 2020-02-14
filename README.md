wechatGo-web
----

### 测试号信息
- 微信号: gh_0ce7dd7fa897 
- appID: wx5a627f35f4d479b0

### 问题
1. 没有autoconfig ，配置无法自动生成
2. session 需要本地支持 memcache （redis 也可以）
3. 其他

### 配置

application/extra 目录下

- environment.example.php --- 环境配置，需删除.example（复制粘贴重命名为environment.php也可）

- 同理/dev下还有 （不一定完整 只要有.example ）
- database.example.ini
- session_config.example.ini
- WeChat.example.ini

