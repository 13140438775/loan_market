#1.1平台
#####本平台是一款专业的贷款导航搜索平台，致力于为贷款用户提供更便捷、更智能的贷款搜索服务。
#1.2 开放平台API接入简述
###1.引言
######平台（下称：平台）对合作机构推出借贷导航开放平台，合作机构可以在其内部系统无缝对接平台的用户资源数据。合作机构通过平台系统API接口获取借款人和借款订单的相关信息（贷款人基本信息、征信信息、审核材料等），完成贷款订单的资质审核，同时合作机构向平台提供API实现订单的绑卡和还款操作，并通过接口最终反馈操作结果给平台。
###2.接入开放平台条件
######1.申请成为平台合作机构
######2.提交机构借贷产品的相关产品数据和介绍信息
######3.获取平台接入标识（ua）、请求签名密钥（signkey）、测试用APP
###3.接口总体说明
####1.接扣Url
#####平台
>mock环境: http://test-mock-poa.xianjincard.com/ <br>
正式环境： http://svc-poa.xianjincard.com/v1/ <br>
测试环境： http://test-svc-poa.xianjincard.com/v1/

#####合作机构
>需提供API统一入口,平台请求的接口以参数中的call作为标识

####2. 鉴权方式
######1.合作机构从开放平台获取平台接入标识（ua）和请求签名密钥（signkey）。
######2.平台接入标识和请求签名密钥由合作机构进行妥善保管以确保安全。
######3.双方每次API交互都需要做请求签名处理。请求数据签名主要采用MD5进行。

####3. 数据交互规范
######1.双方均使用HTTP(S)协议进行数据通信；
######2.请求数据均使用POST方式发送（Content-Type: application/x-www-form-urlencoded）；
######3.双方的接口响应数据字符集必须为utf8编码，且返回的数据各项必须为JSON格式字符串；

####4. 公共请求参数说明

| 参数名称 | 类型| 必选项 | 参数说明|
| :--- | :--- | :--- | :--- |
| ua | string | 是 | 开放平台分配给合作方的唯一标识 |
| call | string | 是 |请求标识；用来唯一标记当前调用的接口|
|args |json| 是 | 接口的调用参数，要求必须JSON String|
|sign | string |  是 | 请求签名，参考附录-签名规则 |
|timestamp| string|否|以秒为单位的UnixTimestamp时间戳|
#####请求接口示例
```
ua: "SH_51_GeiNiHua",
call: "PartnerGate.Order.approveFeedback",
args: {
    "order_id": "59841a25c768b00e27ba3226", 
    "approve_result": "OK",
    "approve_message": "审核通过", 
},
sign: "{签名规则参考 接口请求签名章节}",
timestamp: "1500693926",
```
#####数据返回格式规范
>开放平台服务端响应格式只支持json，返回的数据必须总是包含status和message字段，根据需要包含response字段。 接口响应示例数据结构：

```
{
    status: 1,
    message: "success",
    response: null
}
```
####5. 接入流程图


#1.3 合作机构接口
####分类概述
######此章节的接口均需合作机构提供，由平台发起请求。 平台根据业务需求对合作机构不同的接口发起基于HTTP的POST数据请求
####协议要求
######1.使用HTTP(S)协议进行数据通信；
######2.使用POST方式发送请求数据（Content-Type: application/x-www-form-urlencoded）；
######3.接口请求数据和响应数据的编码必须为utf8编码
######4.接口返回的数据格式必须是JSON格式；
####4.请求参数
| 参数名称 | 类型| 必选项 | 参数说明|
| :--- | :--- | :--- | :--- |
| ua | string | 是 | 开放平台分配给合作方的唯一标识 |
| call | string | 是 |请求标识；用来唯一标记当前调用的接口|
|args |json| 是 | 接口的调用参数，要求必须JSON String|
|sign | string |  是 | 请求签名，参考附录-签名规则 |
|timestamp| string|否|以秒为单位的UnixTimestamp时间戳|
#####请求接口示例
```
ua: "SH-XJ360",
call: "User.isUserAccept",
args: {
    "user_name": "刘先森", 
    "user_phone": "13245678***",
    "user_idcard": "610121190001011212", 
},
sign: "{签名规则参考 接口请求签名章节}",
timestamp: "1500693926"
```
#####数据返回格式规范
>开放平台服务端响应格式只支持json，返回的数据必须总是包含status和message字段，根据需要包含response字段。 接口响应示例数据结构：

```
{
	 status: 1,
     message: "success",
     response: {
        "result": 200, 
        "loan_mode": 0,
        "user_id": "838f5c94a7db105e3948aa40e982427a"
    }
}
```

##1.3.1 用户过滤接口
####1.接口说明
#####用户过滤
#####本接口主要目的是过滤机构黑名单用户以及一些基础风控规则的预拦截。
######平台在导流的过程中将首先根据机构所需的用户客群条件进行筛客，符合筛客条件的人群将会调用此接口进行准入判断。
#####此接口开发过程中需要注意如下几点：
######1.接口存在目的是为判断用户当前是否具有借款权限
######2.如用户当前不可借，则必须准确的返回具体不可借原因以及下次可借时间，用于平台APP前端透传给用户显示
######3.判断用户是否满足走复贷简化流程。复贷简化流程将只传递用户基础信息和订单信息。【简化流程功能暂未开放】
####2.接口标识
>User.isUserAccept

####3.请求参数
| 参数名称 | 类型| 必选项 | 参数说明|
| :--- | :--- | :--- | :--- |
| user_name| string | 是 | 用户姓名 |
| user_phone | string | 是 |用户手机号 (掩后3位)|
|user_idcard |string| 是 | 用户身份证号码 (掩后4位)|
|md5 | string | 否 | md5(手机号+身份证)|

####4.响应参数
| 参数名称 | 类型| 必选项 | 参数说明|
| :--- | :--- | :--- | :--- |
| result| integer | 是 |借款权限 |
| amount | integer | 是 | 最大可待额度，单位：分|
|min_amunt | integer | 否 |最小可贷额度，单位：分|
|terms | array(integer) | 是 | 可贷期限。如：[7,14,30]|
|term_type | integer | 是 | 贷款期限单位。1:按天; 2：按月; 3：按年|
|loan_mode | integer | 是 | 0：标准流程；1：简化流程|
|remark | string | 否 | 其他原因拒绝借款时,次字段说明具体原因|
| can_loan\_time  | string | 是 | 如当前没有借款权限，需告知用户在什么时候才可以借款，精确到天即可.例如：2017-02-29|
####5.请求示例
```
ua: "SH_XJ360_POA",
call: "User.isUserAccept",
args: {
    "user_name": "刘先森",
    "user_phone": "13245678***",
    "user_idcard": "61012119000109****", 
    "md5": "ef6852278c1445930290c127b0454965", 
},
sign: "略...",
timestamp: "1500693926"
```
####6.准入响应示例
```
{
    status: 1,
    message: "success",
    response: {
        "result": 200,
        "amount": 150000,
        "min_amount": 100000,
        "terms": [7,14,30],
        "term_type": 1,   
        "loan_mode": 0
    }
}
```
####7.在贷拒绝准入响应示例
```
{
    status: 1,
    message: "success",
    response: {
        "result": 401,
        "can_loan_time": "2017-10-01"
    }
}
```
####8.其他原因拒绝准入响应示例
```
{
    status: 1,
    message: "success",
    response: {
        "result": 505,
        "can_loan_time": "2017-12-01",
        "remark": "您之前的借款订单未通过审核，2017年12月01日可再次申请。"
    }
}
```
####9.借款权限列表
| 参数名称 | 类型| 
| :--- | :--- |
| 200| 可以借款 |
| 301 | 用户年龄过大或过小 | 
|401 | 用户在机构有未完成的借款 | 
|402 | 用户在机构有不良借款记录 | 
|403 | 该用户是征信系统黑名单用户 |
|505 | 其他原因，禁止用户借款。需在备注说说明原因 | 


##1.3.4 获取绑卡银行列表
#### 1. 接口说明
此接口用户获取合作机构所支持的银行列表
>特别说明：银行编号和银行名称必须使用银行Code映射关系中的值
>
#### 2. 接口标识
>BindCard.getValidBankList
>
#### 3. 请求参数
| 参数   | 类型   | 是否必选   | 描述   | 
|:----:|:----:|:----:|:----|
| card_type   | string   | 否   | 银行卡类型 1 信用卡 2 借记卡   | 

#### 4. 请求示例
```
ua: "SH_XJ360_POA",
call: "BindCard.getValidBankList",
args: {
    "card_type":"1"
},
sign: "略...",
timestamp: "1500693926"
```
#### 5. 响应示例
```
{
    status: 1,
    message: "success",
    response: [
        {
            "bank_name": "工商银行",
            "bank_code": "ICBC",
            "bank_title": "单笔1W/日2W/月2W",
            "bank_icon": "https://h5.u51.com/99fenqi/image/logo/ICBC20161031.png"
        },
        {
            "bank_name": "中国银行",
            "bank_code": "BOC",
            "bank_title": "单笔5W/日20W/月20W",
            "bank_icon": "https://h5.u51.com/99fenqi/image/logo/BOC20161031.png"
        }
    ]
}
```
#### 6. 银行Code映射关系
| 银行名称   | 银行编号   | 
|:----:|:----|
| 工商银行   | ICBC   | 
| 农业银行   | ABC   | 
| 中国银行   | BOC   | 
| 建设银行   | CCB   | 
| 交通银行   | BCOM   | 
| 民生银行   | CMBC   | 
| 招商银行   | CMB   | 
| 邮储银行   | POST   | 
| 平安银行   | PAB   | 
| 中信银行   | CITIC   | 
| 光大银行   | CEB   | 
| 兴业银行   | CIB   | 
| 广发银行   | GDB   | 
| 华夏银行   | HXB   | 
| 南京银行   | NJCB   | 
| 浦发银行   | SPDB   | 
| 北京银行   | BOB   | 
| 杭州银行   | HZB   | 
| 宁波银行   | NBCB   | 
| 浙商银行   | CZB   | 
| 徽商银行   | HSB   | 
| 渤海银行   | CBHB   | 
| 汉口银行   | HKBANK   | 


##1.3.5 订单绑卡接口
#### 1. 接口背景
该接口适用于在机构处进行下单前绑卡以及下单后绑卡两种流程。 区别为：
>推单前绑卡则 order_sn 参数为空。
>推单后绑卡则 所有参数均会传递（除验证码参数会根据绑卡结果传递）

另：
>如果该用户在机构处已有绑定的银行卡，应先验证绑卡信息是否和既有卡信息一致，如验证无误则直接返回绑卡成功即可。 如果机构不允许重新绑卡的情况下请返回绑卡失败【505】，并在message字段中返回具体不允许重新（更换）绑卡的说明信息。

说明:
>**如果机构在绑卡操作时有短信验证码校验，需在第一次请求该接口时发送验证码并返回100，平台将重新携带用户验证码在此请求绑卡接口**
>
#### 2. 接口标识
>BindCard.applyBindCard
>
#### 3. 请求参数
| 参数   | 类型   | 是否必选   | 描述   | 
|:----:|:----:|:----:|:----|
| order_sn   | string   | 否   | 借款订单唯一编号   | 
| bank_code   | string   | 是   | 绑卡银行编码   | 
| user_name   | string   | 是   | 用户姓名   | 
| user_idcard   | string   | 是   | 用户身份证号   | 
| card_number   | string   | 是   | 银行卡号   | 
| card_phone   | string   | 是   | 银行预留手机号   | 
| user_phone   | string   | 是   | 用户准入时的明文手机号   | 
| echo_data   | string   | 是   | 回显数据字段, 此字段仅要求在绑卡结果回调接口中回传即可   | 
| verify_code   | string   | 否   | 绑卡验证码，当已发送验证码之后再次请求提交   | 
| card_type   | string   | 否   | 银行卡类型 1 信用卡 2 借记卡   | 

#### 4. 响应参数
| 参数   | 类型   | 是否必选   | 描述   | 
|:----:|:----:|:----:|:----|
| bind_status   | string   | 是   | 绑卡结果状态   | 
| remark   | string   | 否   | 绑卡状态描述   | 

#### 5. 请求示例
```
ua: "SH_XJ360_POA",
call: "BindCard.applyBindCard",
args: {                 
    "order_sn":"246964109149933",    
    "bank_code":"ICBC",        
    "user_name":"张三",        
    "user_idcard":"610121190001011122",
    "card_number":"6222022005001212723",       
    "card_phone":"13245678901",
    "user_phone":"13370212345",
    "echo_data": "{pid: 123456}",
    "verify_code": "376258",
    "card_type":"1"
},
sign: "略...",
timestamp: "1500693926"
```
#### 6. 响应示例
```
成功示例
{
    status: 1,
    message: "success",
    response: {
        "bind_status": "200",
        "remark": "success",
    }
}
失败示例
{
    status: 1,
    message: "success",
    response: {
        "bind_status": "505",
        "remark": "其它错误原因请备注",
    }
}
```
#### 7. 枚举列表
#### 7.1. 绑卡结果状态列表
| bind_status   | 说明   | 
|:----:|:----|
| 100   | 需短信验证码，请输入已发送的验证码重新绑卡   | 
| 200   | 绑卡成功   | 
| 401   | 认证信息不匹配   | 
| 402   | 请检查卡号对应的银行是否正确   | 
| 403   | 无法验证，银行卡未开通认证支付   | 
| 501   | 绑卡失败次数超限，请24小时后重试！   | 
| 505   | 其它绑卡失败原因，请备注用户前端透传   | 














