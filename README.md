# 微信多商户抽奖系统

## 使用场景
1. 一个2B商家开发的产品，提供给多个2C商家代理销售
2. 该2B商家为每个2C商家提供一个线上抽奖宣传页面
3. 2C商家通过自己的微信公众号将该页面推送给自己的消费者
4. 消费者中奖后到相应的2C商家门店去兑换奖品

## 功能要求
* 2C商家只需要用具备基本的可群发消息公众账号，不需要其他的设置、接口开放和软硬件
* 2C商家可以自定义中奖金额、中奖概率、中奖数量
* 2C商家在告知自己的中奖规则后，收到两个网址：
    * 一个是可以直接推出的抽奖页面
    * 一个是消费者兑奖时的核对页面，兑奖过程要求一般手机用户都能轻松操作
* 2B商家统一管理各2C商家的抽奖活动，包括地址分发、中奖规则管理、中奖用户查询、活动的开始和结束
* 每个参与消费者（微信号）只能中奖一次

## 实现逻辑
* 只有一个微信网页授权链接和相应的一个抽奖页面，不同的2C商家通过不同的品牌名Hash参数来区分
* 使用2B商家微信公众号的网页授权功能来获得消费者的OpenID
* 用户抽奖抽中时，检查数据库是否有记录。没有记录才能真正中奖
* 如果该消费者中奖，将其OpenID记录进数据库，同时记录对应的2C商家品牌名Hash和中奖金额，标记为未兑奖
* 同样也只有一个兑奖页面，以品牌名Hash参数来区分
* 消费者到店兑奖，进入兑奖页面后同样会获得其OpenID。如果数据库里有该OpenID，则显示中奖金额，同时数据记录标记为已兑奖
* 不同2C商家的中奖规则，通过其品牌名Hash可以在抽奖页面动态获得

## 微信公众号接口要求
* 所有2C商家微信公众号需要[群发接口](https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21)
* 2B商家微信公众号需要[网页授权获取用户基本信息接口](https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140842)

## 数据库模式
* merchants(__merchantID__, merchantName)
    key | value
    --|--
    merchantID | 商户名加盐后的MD5值，主键
    merchantName | 商户名

* prizes(__id__, _prizeName_, _merchantID_, probability, total, remaining)
    key | value
    --|--
    id | 自增序号，主键
    merchantID | 外键
    prizeName | 奖品名称
    probabilityPercent |中奖概率的百分数的整数部分
    total | 奖品设置总数
    remaining | 奖品剩余数量

* winners(__openid__, _merchantID_, _prizeName_, received)
    key | value
    --|--
    openid | 用户OpenID，主键
    merchantID | 外键
    prizeName | 外键
    receivedTime | 默认为0表示未兑奖，兑奖后存储时间戳


## 使用方法：
**如果开发者没有权限修改2B商家微信公众平台配置和直接调用微信接口获取OpenID，则需要权限拥有者代为获取OpenID并转发给开发者（同时转发2C商家品牌名Hash）**
1. 2B商家`微信公众平台——开发——基本配置——IP白名单`，填写代码所在的IP地址，否则无法获取
   `access token`，从而出现 `config:invalid signature` 的错误
2. 2B商家`微信公众平台——设置——公众号设置——功能设置——JS接口安全域名`，填写代码所在的域名并上传提
   供的txt文件。否则会出现 `invalid url domain` 的错误。
3. 2B商家`微信公众平台——设置——公众号设置——功能设置——网页授权域名`，填写代码所在的域名并上传提
      供的txt文件。否则无法获取用户OpenID。
