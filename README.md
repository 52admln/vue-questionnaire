# vue-questionnaire

> 简易问卷系统,目前功能正在逐步完善当中...

## 功能
1. 创建问卷、删除问卷、预览问卷
2. 问卷结果统计，样本数据查看，已填写问卷用户查看
3. 使用 Excel 批量导入用户
4. 删除单个用户

## Feature 
1. 编辑问卷
2. 问卷交叉分析
3. 添加用户，搜索用户，批量删除用户

## 安装

``` bash
# 安装依赖
npm install

# 服务热重载地址：localhost:8080
npm run dev

# 为生产环境打包成压缩版本
npm run build

```

## API
待完善...

## 问题

1. 问卷统计

```
// 查询单个选项的所选个数
select *,count(*) as total from result where n_id = 22 and q_id = 59 and o_id = 82
```

```
// 查询题目的总人数
select *,count(*) as total from result where n_id = 22 group by q_id
```

```
// 百分比
( 单个选项的个数 / 总人数 * 100 )  
```



