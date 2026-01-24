define(['table', 'form'], function (Table, Form) {
    var Controller = {
        index: function () {
            Table.init = {
                table_elem: 'list',
                tableId: 'list',
                requests: {
                    index_url: 'app.users/index',
                    add_url: 'app.users/add',
                    edit_url: 'app.users/edit',
                    delete_url: 'app.users/delete',
                },
            };
            Table.render({
                elem: '#' + Table.init.table_elem,
                id: Table.init.tableId,
                url: Fun.url(Table.init.requests.index_url),
                init: Table.init,
                toolbar: ['refresh', 'add', 'delete'],
                cols: [[
                    {checkbox: true},
                    {field: 'id', title: 'ID', width: 80, sort: true},
                    {field: 'openid', title: 'OpenID', minWidth: 200},
                    {field: 'nickname', title: '昵称', width: 120},
                    {field: 'avatar', title: '头像', width: 80, templet: 'Table.templet.image'},
                    {field: 'mobile', title: '手机号', width: 120},
                    {
                        field: 'gender',
                        title: '性别',
                        width: 80,
                        templet: function(d) {
                            if (d.gender == 1) return '<span class="layui-badge layui-bg-blue">男</span>';
                            if (d.gender == 2) return '<span class="layui-badge layui-bg-pink">女</span>';
                            return '<span class="layui-badge layui-bg-gray">未知</span>';
                        }
                    },
                    {field: 'city', title: '城市', width: 100},
                    {field: 'province', title: '省份', width: 100},
                    {
                        field: 'status',
                        title: '状态',
                        width: 100,
                        templet: 'Table.templet.switch',
                        selectList: {0: '禁用', 1: '启用'}
                    },
                    {field: 'create_time', title: '注册时间', width: 160, templet: 'Table.templet.time'},
                    {
                        width: 180,
                        align: 'center',
                        title: '操作',
                        init: Table.init,
                        templet: Table.templet.operat,
                        operat: ['edit', 'delete']
                    }
                ]],
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
                page: true
            });
            Table.api.bindEvent(Table.init.tableId);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindEvent($('form'));
            }
        }
    };
    return Controller;
});
