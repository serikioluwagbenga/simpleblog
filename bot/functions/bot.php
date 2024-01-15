<?php
class bot extends Database
{
    function load_blog()
    {
        $data = $this->api_call("https://eventregistry.org/api/v1/article/getArticles?resultType=articles&keyword=Bitcoin&keyword=Ethereum&keyword=Litecoin&keywordOper=or&lang=eng&articlesSortBy=date&includeArticleConcepts=true&includeArticleCategories=true&articleBodyLen=5000&articlesCount=10&apiKey=f5144915-1624-42f5-8646-35a0cbb7b8ce");
        if (isset($data->articles->results)) {
            // var_dump($data->articles->results);
            $bots = json_decode(json_encode($data->articles->results), true);
            foreach ($bots as $bot) {
                // var_dump ($bot);
                // return;
                if(!isset($bot['title'])) { $bot['title'] = $this->short_text($bot['body'], 30); }
                // $post['slug'] = preg_replace('/[^A-Za-z0-9 ]/', '-', str_replace(" ", "-", $bot['title']));
                if ($this->getall("posts", "title = ?", [$bot['title']], fetch: "") > 0) {
                    continue;
                }
                // $post['post_desc'] = $this->short_text($bot['body'], 5000);
                // '{"en_GB":"'.htmlspecialchars($bot['body']).'"}';
                // $post['image'] = $this->upload_image($bot['image'], $bot['title']);
  
                $data = ["cat_id"=>9, "title"=>$bot['title'], "contents"=>$bot['body'], "date_posted"=>date("Y-m-d H:i:s", strtotime($bot['dateTime']))];
                if (!$this->quick_insert("posts", $data, $data['title'] . " blog added.")) {
                    continue;
                }
                // $get = $this->getall("posts", "post_slug = ?", [$post['post_slug']], fetch: "details");
                // $contents = ["post_id" => $get['id'], "body" => $bot['body'], "blank" => 1];
                // $this->quick_insert("contents", $contents, $post['post_title'] . " content added.");
            }
        }
    }

    function load_blog_type_2() {
        $data = $this->api_call("https://newsdata.io/api/1/news?apikey=pub_3203749e22cd0c7022f3917661839002ba33e&q=coin");
        if (isset($data->results)) {
            $bots = json_decode(json_encode($data->results), true);
            foreach ($bots as $bot) {
            $bot['title'] = preg_replace('/[^A-Za-z0-9 ]/', '-', str_replace(" ", "-", $bot['title']));
            $post['category_id'] = '["22"]';
            $post['admin_id'] = 22;
            $post['created_by'] = "admin";
            if(!isset($bot['title'])) { $bot['title'] = $this->short_text($bot['content'], 30); }
            $post['title'] = '{"en_GB":"'.$bot['title'].'"}';
            $post['slug'] = preg_replace('/[^A-Za-z0-9 ]/', '-', str_replace(" ", "-", $bot['title']));
            if ($this->getall("blogs", "slug = ?", [$post['slug']], fetch: "") > 0) {
                continue;
            }
            // $post['post_desc'] = $this->short_text($bot['body'], 5000);
            $post['blog_content'] = '{"en_GB":"'.$this->RemoveSpecialChar($bot['content']).'"}';  
            // '{"en_GB":"'.htmlspecialchars($bot['body']).'"}';
            $post['image'] = $this->upload_image($bot['image_url'], $bot['title']);
            if($post['image'] == false) { continue;  }
            $post['views'] = rand(700, 2000);
            $post['visibility'] = "public";
            $past['password'] = "12345678";
            $post['featured'] = "yes";
            $post['created_at'] = $this->date_format(strtotime($bot['pubDate']));
            $post['updated_at'] = $this->date_format(strtotime($bot['pubDate']));
            $post['status'] = "publish";
            $post['tag_id'] = '[""]';
            
            if (!$this->quick_insert("blogs", $post, $post['title'] . " blog added.")) {
                continue;
            }
        }
    }
    }
    // {"en_GB":"stocks and trade"}
    function handle_cat($key) {
        $cat = $this->getall("blog_categories", "title = ?", [$key]);
        if(is_array($cat)) {
            return '["'.$cat['id'].'"]';
        }
        if($this->quick_insert("blog_categories", ["title"=>$key])){
            return $this->handle_cat($key);
        }
        return '["22"]';
    }
    function RemoveSpecialChar ($str){
 
        // Using str_ireplace() function 
        // to replace the word 
        $res = str_ireplace( array( '\'', '"',
        ',' , ';', '<', '>', "#", "&",  "$" ), ' ', $str);
   
        // returning the result 
        return $res;
        }
   
    // Given string

   
    // Function calling
    function save_url_img($url, $path, $name = null) {
        $filename = $name ?? uniqid();
        $contents = file_get_contents($url);
        $file_fullname = $filename.$this->get_ext($url);
        if(file_put_contents($path . $file_fullname, $contents)) {
            return  $file_fullname;
        }
        return false;
    }
    function get_ext($path) {
        $dot = '.';
        return strrchr($path, $dot);
    }

    function get_image_info($path) {
    $image_size = getimagesize($path);
    $image_size_kb = $image_size[0] * $image_size[1] * 3 / 1024;
    return ["size"=>$image_size_kb." KB", "dimensions"=>$image_size[0]." x ".$image_size[1]." pixels",];
    }

    function upload_image($url, $title) {
        if(empty($url) || $url == null ) { $url = "img/default.jpeg"; }
        $check = $this->getall("media_uploads", "title = ?", [$title], "id");
        if(is_array($check)) { return $check['id']; }
        $path = "../assets/uploads/media-uploader/";
        $filename = $this->save_url_img($url, $path, "BL-".$title);
        if($filename == false){
            return false;
        }
        $img_info =  $this->get_image_info($url);
        $image = [
            "title"=>$title,
            "path"=>$filename,
            "alt"=>$title,
            "size"=>$img_info['size'],
            "dimensions"=>$img_info['dimensions'],
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s"),
            "type"=>"web",
            "user_id"=>22,
        ];
        if(!$this->quick_insert("media_uploads", $image)){
            return false;
        }
        $check = $this->getall("media_uploads", "title = ?", [$image['title']], "id");
        if(!is_array($check)) { return false; }
        return $check['id'];
    }

}
?>