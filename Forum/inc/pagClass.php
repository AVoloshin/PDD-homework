<?php
class paginator{
    const PAGESIZE=5;
    public static function getPaginator(array $data)
    {
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $dbSize = count($data);
        $pageNum = ceil($dbSize /self::PAGESIZE);
        $html="<div><ul class=\"pagination\">";
        $id=isset($_GET['id'])?$_GET['id']:NULL;
        if($currentPage == 1){
            $html.= "<li class=\"disabled\"><a href=\"#\">&laquo;</a></li>"; //ссылка для перехода на предыдущую страницу
        }else {
            $url=paginator::generateUrl($id,($currentPage-1));
            $html.= "<li><a href=".$url.">&laquo;</a></li>";
        }
        for ($i=1;$i<=$pageNum;$i++) {
            if($i!=$currentPage)
            {
                $url=paginator::generateUrl($id,$i);
                $html.="<li><a href=".$url.">$i</a></li>";//ссылка для перехода на другие страницы
            } else {
                $html.="<li class='active'><a href=\"#\">$i</a></li>";//ссылка для перехода на текущую страницу
            }
        }
        if($currentPage==$pageNum){
            $html.="<li class=\"disabled\"><a href=\"#\">&raquo;</a></li>"; //ссылка для перехода на следующую страницу
        } else{
            $url=paginator::generateUrl($id,($currentPage+1));
            $html.="<li><a href=".$url.">&raquo;</a></li>";
        }
        $html.='</ul></div>';

        return $html;
    }

    public static function generateUrl($topicId=NULL, $pagenum=NULL, $script=NULL){
        $array=array("id"=>$topicId,"page"=>$pagenum);
        if(!(isset($script))){
            $script=$_SERVER['PHP_SELF'];
        }
        if(isset($_GET['page'])){
            $pagenum=$_GET['page'];
        }
        $query=http_build_query($array);
        $url='http://'.$_SERVER['HTTP_HOST'].$script.'?'.$query.'';
        return $url;
    }
}