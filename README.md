# dbapi
php单文件，mysql数据库CRUD转API接口模式

## 请求格式：index.php/table_name/xxx/xxx

### 查询全部信息
GET: index.php/table_name

### 显示第1页，默认每页10条，分页以+开头
GET: index.php/table_name/+1

### 条件查询
GET: index.php/table_name/id>1

### 显示第1页，每页16条
GET: index.php/table_name/+1-16

### 条件分页
GET: index.php/table_name/+1-16/id>10

### 删除id=16的记录
GET: index.php/table_name/-16

### 条件删除：以-开头
GET: index.php/table_name/-id>15

### 插入：必须为0
- POST: index.php/table_name/0
- post过来的数据，字段为列名，值为数据

### 更新：必须大于0的数字或者条件字符串
- POST: index.php/table_name/1
- POST: index.php/table_name/id>1
- post过来的数据，字段为列名，值为数据
