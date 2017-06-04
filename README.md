# vue-questionnaire

> 简易问卷系统,目前功能正在逐步完善当中...

## 安装

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build

```

## API

## 路由

`/login` 登录

`/platform/list` 问卷列表

`/platform/new` 新建问卷

`/platform/new/edit` 编辑问卷

`/view` 问卷展示


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



