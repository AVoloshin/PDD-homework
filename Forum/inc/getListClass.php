<?php
include_once('pdoclass.php');
class getList extends dbDataProcessing{

    public function getTopicsList(){
        $getList=$this->DBH->query("SELECT topics.id_topic ,topics.topic_name, topics.topic_startdate, vdn_users.username, posts.post_date, posts.post_author
        FROM topics, posts, vdn_users
        WHERE topics.id_topic=posts.post_topic AND topics.topic_starter=vdn_users.id_user
        GROUP BY topics.topic_name
        ORDER BY posts.post_date DESC");
        $topicList=$getList->fetchAll(PDO::FETCH_ASSOC);
        return $topicList;
    }
    
    public function readTopicList(array $topicList){
        $page=isset($_GET['page']) ? $_GET['page'] : 1;
        $begin=($page-1)*paginator::PAGESIZE;
        $pageList=array_slice(array_reverse($topicList), $begin, paginator::PAGESIZE);
        $html='<ul class="list-group">';
        foreach($pageList as $value){
            $new=$value;
            $topic_name=$new['topic_name'];
            $url=paginator::generateUrl($new['id_topic'],$page,'/forum/posts.php');
            $html.="<li class=list-group-item>"."<a href="."$url".">$new[topic_name]"."<a>"." создана "."$new[topic_startdate]".", автор: "."$new[username]".", последнее сообщение: "."$new[post_date]"."</li>";

        }
        return $html;
    }
    public function getPostsList($topicId){
        $getPosts=$this->DBH->query("
        SELECT vdn_users.username,posts.post_date,posts.post_message,topics.topic_name
        FROM posts, topics, vdn_users
        WHERE posts.post_topic=topics.id_topic AND topics.id_topic='"."$topicId"."' AND posts.post_author=vdn_users.id_user
        ORDER BY post_date DESC");
        $postList=$getPosts->fetchAll(PDO::FETCH_ASSOC);
        return $postList;
    }
    public function readPostList(array $postList){
        $page=isset($_GET['page']) ? $_GET['page'] : 1;
        $begin=($page-1)*paginator::PAGESIZE;
        $pageList=array_slice(array_reverse($postList), $begin, paginator::PAGESIZE);
        $html='<ul class="list-group">';
        foreach($pageList as $value){
            $new=$value;
            $html.="<li class=list-group-item>"."$new[username] "."$new[post_date] "."написал: "."$new[post_message]"."</li>";

        }
        return $html;
    }
}

/*
$postList=$getList->getPostsList('Тестовая тема 2');
$posts=$getList->readPostList($postList);
echo $posts;*/