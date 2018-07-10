<?php

/**
 * 销售渠道-公司管理
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 销售渠道-公司列表
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'list')
{
    /* 检查权限 */
//    admin_priv('company_manage');

    $smarty->assign('ur_here',      $_LANG['c_company_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['c_company_add'], 'href'=>'custom_company.php?act=add'));

    $company_list = company_list();
    include_once(ROOT_PATH.'includes/cls_certificate.php');
    $cert = new certificate();
    $is_bind_crm = $cert->is_bind_sn('ecos.taocrm','bind_type');
    $smarty->assign('is_bind_crm',      $is_bind_crm);
    $smarty->assign('company_list',     $company_list['company_list']);
    $smarty->assign('filter',           $company_list['filter']);
    $smarty->assign('record_count',     $company_list['record_count']);
    $smarty->assign('page_count',       $company_list['page_count']);
    $smarty->assign('full_page',        1);
    $smarty->assign('sort_company_id', '<img src="images/sort_desc.gif">');
    $smarty->assign('pageHtml','custom_company_list.htm');
    assign_query_info();
    $smarty->display('custom_company_list.htm');
}

/*------------------------------------------------------ */
//-- ajax返回公司列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $company_list = company_list();

    $smarty->assign('company_list',     $company_list['company_list']);
    $smarty->assign('filter',           $company_list['filter']);
    $smarty->assign('record_count',     $company_list['record_count']);
    $smarty->assign('page_count',       $company_list['page_count']);
    $sort_flag  = sort_flag($company_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('custom_company_list.htm'), '', array('filter' => $company_list['filter'], 'page_count' => $company_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加公司页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 检查权限 */
//    admin_priv('company_manage');

    $smarty->assign('ur_here',          $_LANG['c_company_add']);
    $smarty->assign('action_link',      array('text' => $_LANG['c_company_list'], 'href'=>'custom_company.php?act=list'));
    $smarty->assign('form_action',      'insert');

    assign_query_info();
    $smarty->display('custom_company_add.htm');
}

/*------------------------------------------------------ */
//-- 添加公司
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert')
{
    /* 检查权限 */
//    admin_priv('company_manage');
    $company_name = empty($_POST['company_name']) ? '' : trim($_POST['company_name']);

    if (empty($_POST['company_name']))
    {
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
        sys_msg($_LANG['js_languages']['company_name_empty'], 0, $link);
    }

    /* 查看公司名称是否有重复 */
    $sql = "SELECT COUNT(*) FROM " .$ecs->table('custom_company'). " WHERE company_name = '$company_name'";
    if ($db->getOne($sql) > 0)
    {
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
        sys_msg($_LANG['js_languages']['company_name_exist'], 0, $link);
    }

    /* 插入数据 */
    $sql = "INSERT INTO ".$ecs->table('custom_company'). " (company_name)
    VALUES ('$_POST[company_name]')";

    $db->query($sql);
    /* 记录管理员操作 */
    admin_log($_POST['company_name'], 'add', 'custom_company');

    /* 提示信息 */
    $link[] = array('text' => $_LANG['go_back'], 'href'=>'custom_company.php?act=list');
    sys_msg(sprintf($_LANG['add_success'], $company_name), 0, $link);
}

/*------------------------------------------------------ */
//-- 编辑公司
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'edit')
{
    /* 检查权限 */
//    admin_priv('company_manage');

    $sql = "SELECT * FROM " .$ecs->table('custom_company'). " WHERE company_id='".intval($_REQUEST['id'])."'";

    $custom_company = $db->GetRow($sql);
    $custom_company['custom_company'] = addslashes($row['custom_company']);

    if (!$custom_company)
    {
          $link[] = array('text' => $_LANG['go_back'], 'href'=>'custom_company.php?act=list');
          sys_msg($_LANG['company_name_invalid'], 0, $links);
     }

    assign_query_info();
    $smarty->assign('ur_here',          $_LANG['c_company_edit']);
    $smarty->assign('action_link',      array('text' => $_LANG['c_company_list'], 'href'=>'custom_company.php?act=list'));
    $smarty->assign('custom_company',             $custom_company);
    $smarty->assign('form_action',      'update');
    $smarty->display('custom_company_add.htm');
}

/*------------------------------------------------------ */
//-- 更新公司
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'update')
{
    /* 检查权限 */
//    admin_priv('company_manage');
    $company_id = empty($_POST['company_id']) ? '' : trim($_POST['company_id']);
    $company_name = empty($_POST['company_name']) ? '' : trim($_POST['company_name']);

    if (empty($_POST['company_name']))
    {
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
        sys_msg($_LANG['js_languages']['company_name_empty'], 0, $link);
    }

    /* 查看公司名称是否有重复 */
    $sql = "SELECT COUNT(*) FROM " .$ecs->table('custom_company'). " WHERE company_name = '$company_name' AND company_id != '$company_id'";
    if ($db->getOne($sql) > 0)
    {
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
        sys_msg($_LANG['js_languages']['company_name_exist'], 0, $link);
    }

    /* 更新信息 */
    $sql = "UPDATE " .$ecs->table('custom_company'). " SET ".
        "company_name = '$company_name' ".
        "WHERE company_id = '$company_id'";
    $db->query($sql);

    /* 记录管理员操作 */
    admin_log($company_name, 'edit', 'custom_company');

    /* 提示信息 */
    $link[0]['text']    = $_LANG['goto_list'];
    $link[0]['href']    = 'custom_company.php?act=list';
    $link[1]['text']    = $_LANG['go_back'];
    $link[1]['href']    = 'javascript:history.back()';

    sys_msg($_LANG['update_success'], 0, $link);

}

/*------------------------------------------------------ */
//-- 删除公司
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove')
{
    /* 检查权限 */
//    admin_priv('company_manage');

    $sql = "SELECT company_name FROM " . $ecs->table('custom_company') . " WHERE company_id = '" . $_GET['id'] . "'";
    $company_name = $db->getOne($sql);

    $sql = "DELETE FROM " . $ecs->table('custom_company') . " WHERE company_id = '" . $_GET['id'] . "'";
    $db->query($sql);

    /* 记录管理员操作 */
    admin_log(addslashes($company_name), 'remove', 'custom_company');

    /* 提示信息 */
    $link[] = array('text' => $_LANG['go_back'], 'href'=>'custom_company.php?act=list');
    sys_msg(sprintf($_LANG['remove_success'], $company_name), 0, $link);
}

/**
 *  返回公司列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function company_list()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keywords'] = json_str_iconv($filter['keywords']);
        }

        $filter['sort_by']    = empty($_REQUEST['sort_by'])    ? 'company_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC'     : trim($_REQUEST['sort_order']);

        $ex_where = ' WHERE 1 ';
        if ($filter['keywords'])
        {
            $ex_where .= " AND company_name LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
        }

        $filter['record_count'] = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('custom_company') . $ex_where);

        /* 分页大小 */
        $filter = page_and_size($filter);
        $sql = "SELECT company_id, company_name ".
                " FROM " . $GLOBALS['ecs']->table('custom_company') . $ex_where .
                " ORDER by " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

        $filter['keywords'] = stripslashes($filter['keywords']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $company_list = $GLOBALS['db']->getAll($sql);

    $arr = array('company_list' => $company_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

?>