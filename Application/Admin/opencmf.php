<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
// 模块信息配置
return array (
  'info' => 
  array (
    'name' => 'Admin',
    'title' => '系统',
    'icon' => 'fa fa-cog',
    'icon_color' => '#3CA6F1',
    'description' => '核心系统',
    'developer' => '南京科斯克网络科技有限公司',
    'website' => 'http://www.lingyun.net',
    'version' => '1.0.0',
  ),
  'admin_menu' => 
  array (
    1 => 
    array (
      'pid' => '0',
      'title' => '系统',
      'icon' => 'fa fa-cog',
      'level' => 'system',
      'id' => 1,
    ),
    2 => 
    array (
      'pid' => '1',
      'title' => '系统功能',
      'icon' => 'fa fa-folder-open-o',
      'id' => 2,
    ),
    3 => 
    array (
      'pid' => '2',
      'title' => '系统设置',
      'icon' => 'fa fa-wrench',
      'url' => 'Admin/Config/group',
      'id' => 3,
    ),
    4 => 
    array (
      'pid' => '3',
      'title' => '修改设置',
      'url' => 'Admin/Config/groupSave',
      'id' => 4,
    ),
    5 => 
    array (
      'pid' => '2',
      'title' => '导航管理',
      'icon' => 'fa fa-map-signs',
      'url' => 'Admin/Nav/index',
      'id' => 5,
    ),
    6 => 
    array (
      'pid' => '5',
      'title' => '新增',
      'url' => 'Admin/Nav/add',
      'id' => 6,
    ),
    7 => 
    array (
      'pid' => '5',
      'title' => '编辑',
      'url' => 'Admin/Nav/edit',
      'id' => 7,
    ),
    13 => 
    array (
      'pid' => '2',
      'title' => '配置管理',
      'icon' => 'fa fa-cogs',
      'url' => 'Admin/Config/index',
      'id' => 13,
    ),
    14 => 
    array (
      'pid' => '13',
      'title' => '新增',
      'url' => 'Admin/Config/add',
      'id' => 14,
    ),
    15 => 
    array (
      'pid' => '13',
      'title' => '编辑',
      'url' => 'Admin/Config/edit',
      'id' => 15,
    ),
    17 => 
    array (
      'pid' => '2',
      'title' => '上传管理',
      'icon' => 'fa fa-upload',
      'url' => 'Admin/Upload/index',
      'id' => 17,
    ),
    18 => 
    array (
      'pid' => '17',
      'title' => '上传文件',
      'url' => 'Admin/Upload/upload',
      'id' => 18,
    ),
    19 => 
    array (
      'pid' => '17',
      'title' => '删除文件',
      'url' => 'Admin/Upload/delete',
      'id' => 19,
    ),
    21 => 
    array (
      'pid' => '17',
      'title' => '下载远程图片',
      'url' => 'Admin/Upload/downremoteimg',
      'id' => 21,
    ),
    22 => 
    array (
      'pid' => '17',
      'title' => '文件浏览',
      'url' => 'Admin/Upload/fileManager',
      'id' => 22,
    ),
    23 => 
    array (
      'pid' => '1',
      'title' => '系统权限',
      'icon' => 'fa fa-folder-open-o',
      'id' => 23,
    ),
    24 => 
    array (
      'pid' => '23',
      'title' => '用户管理',
      'icon' => 'fa fa-user',
      'url' => 'Admin/User/index',
      'id' => 24,
    ),
    25 => 
    array (
      'pid' => '24',
      'title' => '新增',
      'url' => 'Admin/User/add',
      'id' => 25,
    ),
    26 => 
    array (
      'pid' => '24',
      'title' => '编辑',
      'url' => 'Admin/User/edit',
      'id' => 26,
    ),
    28 => 
    array (
      'pid' => '23',
      'title' => '管理员管理',
      'icon' => 'fa fa-lock',
      'url' => 'Admin/Access/index',
      'id' => 28,
    ),
    29 => 
    array (
      'pid' => '28',
      'title' => '新增',
      'url' => 'Admin/Access/add',
      'id' => 29,
    ),
    30 => 
    array (
      'pid' => '28',
      'title' => '编辑',
      'url' => 'Admin/Access/edit',
      'id' => 30,
    ),
    32 => 
    array (
      'pid' => '23',
      'title' => '用户组管理',
      'icon' => 'fa fa-sitemap',
      'url' => 'Admin/Group/index',
      'id' => 32,
    ),
    33 => 
    array (
      'pid' => '32',
      'title' => '新增',
      'url' => 'Admin/Group/add',
      'id' => 33,
    ),
    34 => 
    array (
      'pid' => '32',
      'title' => '编辑',
      'url' => 'Admin/Group/edit',
      'id' => 34,
    ),
    36 => 
    array (
      'pid' => '1',
      'title' => '扩展中心',
      'icon' => 'fa fa-folder-open-o',
      'id' => 36,
    ),
    44 => 
    array (
      'pid' => '36',
      'title' => '功能模块',
      'icon' => 'fa fa-th-large',
      'url' => 'Admin/Module/index',
      'id' => 44,
    ),
    45 => 
    array (
      'pid' => '44',
      'title' => '安装',
      'url' => 'Admin/Module/install',
      'id' => 45,
    ),
    46 => 
    array (
      'pid' => '44',
      'title' => '卸载',
      'url' => 'Admin/Module/uninstall',
      'id' => 46,
    ),
    47 => 
    array (
      'pid' => '44',
      'title' => '更新信息',
      'url' => 'Admin/Module/updateInfo',
      'id' => 47,
    ),
    49 => 
    array (
      'pid' => '36',
      'title' => '插件管理',
      'icon' => 'fa fa-th',
      'url' => 'Admin/Addon/index',
      'id' => 49,
    ),
    50 => 
    array (
      'pid' => '49',
      'title' => '安装',
      'url' => 'Admin/Addon/install',
      'id' => 50,
    ),
    51 => 
    array (
      'pid' => '49',
      'title' => '卸载',
      'url' => 'Admin/Addon/uninstall',
      'id' => 51,
    ),
    52 => 
    array (
      'pid' => '49',
      'title' => '运行',
      'url' => 'Admin/Addon/execute',
      'id' => 52,
    ),
    53 => 
    array (
      'pid' => '49',
      'title' => '设置',
      'url' => 'Admin/Addon/config',
      'id' => 53,
    ),
    54 => 
    array (
      'pid' => '49',
      'title' => '后台管理',
      'url' => 'Admin/Addon/adminList',
      'id' => 54,
    ),
    55 => 
    array (
      'pid' => '54',
      'title' => '新增数据',
      'url' => 'Admin/Addon/adminAdd',
      'id' => 55,
    ),
    56 => 
    array (
      'pid' => '54',
      'title' => '编辑数据',
      'url' => 'Admin/Addon/adminEdit',
      'id' => 56,
    ),
    58 => 
    array (
      'id' => 58,
      'pid' => '5',
      'title' => '禁用',
      'url' => 'Admin/Nav/setStatus/status/forbid',
      'icon' => 'fa ',
    ),
    59 => 
    array (
      'pid' => '5',
      'title' => '启用',
      'url' => 'Admin/Nav/setStatus/status/resume',
      'icon' => 'fa ',
      'id' => 59,
    ),
    60 => 
    array (
      'pid' => '5',
      'title' => '删除',
      'url' => 'Admin/Nav/setStatus/status/delete',
      'icon' => 'fa ',
      'id' => 60,
    ),
    61 => 
    array (
      'pid' => '13',
      'title' => '禁用',
      'url' => 'Admin/Config/setStatus/status/forbid',
      'icon' => 'fa ',
      'id' => 61,
    ),
    62 => 
    array (
      'pid' => '13',
      'title' => '启用',
      'url' => 'Admin/Config/setStatus/status/resume',
      'icon' => 'fa ',
      'id' => 62,
    ),
    63 => 
    array (
      'pid' => '13',
      'title' => '删除',
      'url' => 'Admin/Config/setStatus/status/delete',
      'icon' => 'fa ',
      'id' => 63,
    ),
    64 => 
    array (
      'pid' => '17',
      'title' => '禁用',
      'url' => 'Admin/Upload/setStatus/status/forbid',
      'icon' => 'fa ',
      'id' => 64,
    ),
    65 => 
    array (
      'pid' => '17',
      'title' => '启用',
      'url' => 'Admin/Upload/setStatus/status/resume',
      'icon' => 'fa ',
      'id' => 65,
    ),
    66 => 
    array (
      'pid' => '17',
      'title' => '删除',
      'url' => 'Admin/Upload/setStatus/status/delete',
      'icon' => 'fa ',
      'id' => 66,
    ),
    67 => 
    array (
      'pid' => '24',
      'title' => '禁用',
      'url' => 'Admin/User/setStatus/status/forbid',
      'icon' => 'fa ',
      'id' => 67,
    ),
    68 => 
    array (
      'pid' => '24',
      'title' => '启用',
      'url' => 'Admin/User/setStatus/status/resume',
      'icon' => 'fa ',
      'id' => 68,
    ),
    69 => 
    array (
      'pid' => '24',
      'title' => '删除',
      'url' => 'Admin/User/setStatus/status/delete',
      'icon' => 'fa ',
      'id' => 69,
    ),
    70 => 
    array (
      'pid' => '24',
      'title' => '回收',
      'url' => 'Admin/User/setStatus/status/recyle',
      'icon' => 'fa ',
      'id' => 70,
    ),
    71 => 
    array (
      'pid' => '28',
      'title' => '禁用',
      'url' => 'Admin/Access/setStatus/status/forbid',
      'icon' => 'fa ',
      'id' => 71,
    ),
    72 => 
    array (
      'pid' => '28',
      'title' => '启用',
      'url' => 'Admin/Access/setStatus/status/resume',
      'icon' => 'fa ',
      'id' => 72,
    ),
    73 => 
    array (
      'pid' => '28',
      'title' => '删除',
      'url' => 'Admin/Access/setStatus/status/delete',
      'icon' => 'fa ',
      'id' => 73,
    ),
    74 => 
    array (
      'pid' => '32',
      'title' => '禁用',
      'url' => 'Admin/Group/setStatus/status/forbid',
      'icon' => 'fa ',
      'id' => 74,
    ),
    75 => 
    array (
      'pid' => '32',
      'title' => '启用',
      'url' => 'Admin/Group/setStatus/status/resume',
      'icon' => 'fa ',
      'id' => 75,
    ),
    76 => 
    array (
      'pid' => '32',
      'title' => '删除',
      'url' => 'Admin/Group/setStatus/status/delete',
      'icon' => 'fa ',
      'id' => 76,
    ),
    77 => 
    array (
      'pid' => '44',
      'title' => '禁用',
      'url' => 'Admin/Module/setStatus/status/forbid',
      'icon' => 'fa ',
      'id' => 77,
    ),
    78 => 
    array (
      'pid' => '44',
      'title' => '启用',
      'url' => 'Admin/Module/setStatus/status/resume',
      'icon' => 'fa ',
      'id' => 78,
    ),
    79 => 
    array (
      'pid' => '54',
      'title' => '禁用',
      'url' => 'Admin/Addon/setStatus/status/forbid',
      'icon' => 'fa ',
      'id' => 79,
    ),
    80 => 
    array (
      'pid' => '54',
      'title' => '启用',
      'url' => 'Admin/Addon/setStatus/status/resume',
      'icon' => 'fa ',
    ),
  ),
)
;