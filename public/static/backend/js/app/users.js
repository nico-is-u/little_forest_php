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
                    {field: 'avatar', title: '头像', width: 80, templet: 'Table.templet.image'},
                    {field: 'mobile', title: '手机号', width: 120},
                    {
                        field: 'status',
                        title: '状态',
                        width: 100,
                        templet: 'Table.templet.switch',
                        selectList: {0: '禁用', 1: '启用'}
                    },
                    {field: 'balance', title: '余额', minWidth: 120},
                    {
                        minWidth: 180,
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
