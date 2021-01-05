<?php
include "./config.php";
include "./register.php";
include "./post.php";
include "./stats.php";

// Task 1
$register_obj = new Register("ju16a6m81mhid5ue1z3v2g0uh", "mahmudul.jhony@gmail.com", "mahmudul");
$resonse_data = $register_obj->register_user($URL_REG);

/**
 * Task 2
 * To see the output for this task2 activate this line "echo $res_task2;" from below
 */
$sl_token = $register_obj->get_sl_token($resonse_data);
$page = 1;
$post_obj = new Post($sl_token, $page);
$res = $post_obj->get_posts($URL_POST);
$res_task2 = json_encode($res);
//echo $res_task2;

/**
 * Task 3
 * To see the output for this task3 activate this line "echo $res_task3;" from below
 */
$page = 1;
$posts = array();
while ($page < 11)
{
    $post_obj = new Post($sl_token, $page);
    $resp = $post_obj->get_posts($URL_POST);
    $posts = array_merge($posts,$resp);
    $page++;
}
$res_task3 = json_encode($posts);
//echo $res_task3;

/**
 * Task 4
 * To see the output for this task4 activate this line "echo $res_task4;" from below
*/
$stat_obj = new Stat($posts);
$stat_data = $stat_obj->statistics();
$res_task4 = json_encode($stat_data);
echo $res_task4;

?>