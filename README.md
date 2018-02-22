# vue-questionnaire

> 简易问卷系统,目前功能正在逐步完善中...

> 由于本系统用于校内学生，所以在提交问卷时需要先查看用户的有效性（参与问卷调查的人员是可控的），如不需要此功能，可以自行修改代码。

## 功能
1. 创建问卷、删除问卷、预览问卷、编辑问卷截止时间
2. 问卷结果统计，样本数据查看，已填写问卷用户查看
3. 使用 Excel 批量导入用户、批量删除用户，单个添加、修改、搜索、删除用户
4. 问卷调查页面适配移动端
5. 问卷单题目交叉分析

## TODO
1. 编辑问卷（未发布状态）
2. 查看问题选项所选人员信息（姓名、班级）
3. 修改用户已填写的选项

## 安装

``` bash
# 安装依赖
npm install

# 服务热重载地址：localhost:8080
npm run dev

# 为生产环境打包成压缩版本
npm run build

```

## 前台配置
### proxyTable

如果遇到访问接口出现

> Error occured while trying to proxy to: localhost:8080/api/admin/test`

可以参照下面方法解决：

如将 `/api` 文件夹放在了 PHP 环境中的 `vue-questionnaire` 文件夹中，且访问地址为：`http://localhost:8888/vue-questionnaire/api` ，那么可以根据我的配置来使用了。

同时也可以根据本地PHP环境的访问地址来修改 `/config/index.js` 中 `proxyTabel` 相关配置。

## 后台配置
数据库文件： `api/database.sql`，使用前请先导入。

CI框架数据库连接配置信息请先设置。