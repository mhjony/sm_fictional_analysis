<?php
class Stat {
    public $posts;

    function __construct($posts) {
        $this->posts = $posts;
    }

    /**
     * This function returns average character length of posts per month
     */
    function avg_char_len_per_month($messages, $dates){
        $msg_length = 0;
        foreach ($messages as $message)
            $msg_length = $msg_length + strlen($message);

        $start_date = date_create($dates[0]);
        $end_date = date_create($dates[count($dates) - 1]);
        $month_diff = date_diff($start_date, $end_date);
        $month_num = (array) $month_diff;

        if ($month_num == 0)
            $avg_char_len_per_month = $msg_length;
        else
            $avg_char_len_per_month = $msg_length / $month_num["m"];
        return $avg_char_len_per_month;
    }

    /**
     * This function returns Average number of posts per user per month
     */
    function avg_post_per_month($messages, $dates, $users){
        $start_date = date_create($dates[0]);
        $end_date = date_create($dates[count($dates) - 1]);
        $month_diff = date_diff($start_date, $end_date);
        $month_num = (array) $month_diff;

        if ($month_num["m"] == 0)
            $avg_post_per_month = count($messages);
        else
            $avg_post_per_month = count($messages) / $month_num["m"];

        $avg_num_post_per_usr_per_month = $avg_post_per_month / count(array_unique($users));
        return $avg_num_post_per_usr_per_month;
    }

    /**
     * This function returns an array which contains
     * total number of posts split by week number
     */
    function weekly_total_post($dates, $messages){
        $msg_num = 0;
        $i = 0;
        $weeks = array();
        $dates = array_reverse($dates);
        $w1 = date("YW", strtotime($dates[0]));

        while ($i < count($messages))
        {
            $w2 = date("YW", strtotime($dates[$i]));
            if ($w1 == $w2)
                $msg_num = $msg_num + 1;
            else
            {
                $week_num = date("Y", strtotime($dates[$i]))."_".date("W", strtotime($dates[$i]));
                $weeks = array_merge($weeks, array($week_num => $msg_num));
                $msg_num = 0;
                $w1 = date("YW", strtotime($dates[$i]));
                --$i;
            }
            $i++;
        }
        return $weeks;
    }
    
    /**
     * This functions returns an array
     * which contains Longest post by character length per month
     */
    function longest_post_per_month($dates, $messages){
        $i = 0;
        $max_len = 0;
        $long_post = array();
        $dates = array_reverse($dates);
        $m1 = date("m", strtotime($dates[0]));
        
        while ($i < count($messages))
        {
            $m2 = date("m", strtotime($dates[$i]));
            if ($m1 == $m2)
            {
                $str_len = strlen($messages[$i]);
                if ($max_len < $str_len)
                {
                    $month_num = date("Y", strtotime($dates[$i]))."_".date("m", strtotime($dates[$i]));
                    $long_post = array_merge($long_post, array($month_num => $messages[$i]));
                    $max_len = $str_len;
                }
            }
            else
            {
                $m1 = date("m", strtotime($dates[$i]));
                $max_len = 0;
                --$i;
            }
            $i++;
        }
        return $long_post;
    }

    function statistics(){
        $items = array();
        $dates = array();
        $messages = array();
        $users = array();
        $i = 0;

        while ($i < count($this->posts))
        {
            $single_date = substr($this->posts[$i]["created_time"], 0, 10);
            $single_message = $this->posts[$i]["message"];
            array_push($dates, $single_date);
            array_push($messages, $single_message);
            array_push($users, $this->posts[$i]["from_id"]);
            $i++;
        }
        $avg_char_len_per_month = $this->avg_char_len_per_month($messages, $dates); //a
        $longest_post_per_month = $this->longest_post_per_month($dates, $messages); //b
        $total_post_by_week = $this->weekly_total_post($dates, $messages); //c   
        $avg_post_per_usr_per_month = $this->avg_post_per_month($messages, $dates, $users); //d
        
        $result = array_merge($items, array('avg_char_len_per_month' => $avg_char_len_per_month, 
        'longest_post_per_month' => $longest_post_per_month,'total_post_by_week' => $total_post_by_week, 
        'avg_post_per_usr_per_month' => $avg_post_per_usr_per_month));
    
        return ($result);
    }
}
?>