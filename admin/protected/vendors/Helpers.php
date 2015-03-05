<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Helper
 *
 * @author Administrator
 */
class Helpers {
    
    /**
     * 
     */
    public static function checkAccessRule($options = null, $param = null){
        $errMes = 'Bạn không có quyền thực hiện hành động này.';
        $id = Yii::app()->user->id;
        if(!isset($id))
            Yii::app()->request->redirect('/admin/site/logout');

        $staff = User::model()->findByPk($id);

        if(!isset($staff))
            Yii::app()->request->redirect('/admin/site/logout');

        if(!in_array( $staff->is_super, $param, true)){
            throw new CHttpException(403, $errMes);
        }
    }

    public static function getFirstImg($contentHTML){
        $image = '';
        preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $contentHTML, $image);
        return ($image != null) ? $image[1] : substr(Yii::app()->request->getBaseUrl(true),0,-5).'upload/images/no_img.jpg';
    }

    public static function getNumChars($contentHTML, $num){
        $contentHTML = strip_tags($contentHTML);
        $worldList = array_slice(explode(' ', $contentHTML), 0, $num - 1);
        return (implode($worldList) != '') ? implode(' ',$worldList).' ...' : '';
    }

    /************************************************************************************
     * Function to make slideshow data
     * @param type $limit
     * @return array
     */
    public static function getSlideShowData($limit) {
        $slideShow = array();

        for ($i=0; $i<$limit; $i++) {
            $slideShow[] = array(
                'image' => Yii::app()->request->baseUrl . "/common/img/dummy_01.png",
                'linkItem' => 'test' . $i,
                'text' => 'キャプション ' . $i
            );
        }

        return $slideShow;
    }

    /**
     * Escape some characters for like expression
     */
    public static function escapeForLike($keyword) {
        $escapeChars = array('%', '_');
        $replaceChars = array('\\%', '\\_');
        return str_replace($escapeChars, $replaceChars, $keyword);
    }

    /**
     * Manipulte period time
     *
     * @param type $startDate
     * @param type $endDate
     * @return string
     */
    public static function manipulateDate($startDate, $endDate) {
        $date = '';
        if ($startDate != null && $endDate != null) {
            $startDateArray = explode('-', $startDate);
            $endDateArray = explode('-', $endDate);
            // if startDate and endDate have the same month
            if ($startDateArray[1] == $endDateArray[1]) {
                $date = $startDateArray[1] . '月' . $startDateArray[2] . '日〜' .  $endDateArray[2] . '日';
            }
            // if startDate and endDate have difference month
            else {
                $date = $startDateArray[1] . '月' . $startDateArray[2] . '日〜' . $endDateArray[1] . '月' .  $endDateArray[2] . '日';
            }
        }


        return $date;
    }

    /**
     * Convert date
     */
    public static function convertDate($date) {
        if ($date != null) {
            $data = explode(" ", $date);
            $first = explode("-", $data[0]);
            $second = explode(':', $data[1]);
            $result = $first[0] . '年' . $first[1] . '月' . $first[2] . '日 '
                    . $second[0] . '時' . $second[1] . '分' . $second[2] . '秒';

            return $result;
        }
        return "";
    }

    /**
     * Check positive number
     *
     * @param type $number
     * @return boolean
     */
    public static function checkPositiveNumber($number){
        if (is_numeric($number) && $number > 0){
            return true;
        }
        return false;
    }
    public  static  function getChilds($cat_id){
        $childs = ContentCategories::model()->findAllByAttributes(array('parent_id'=>$cat_id));
        return $childs;
    }
    public static function getRoundLabel($round,$num)
    {
         if($round->round == '0')
         {
            return '事前情報';
         }
         elseif($round->round == $num -1)
         {
            return '最終日';
         }
         else
         {
            return sprintf('%d日目',$round->round);
         }
    }

    /**
     * export csv file
     *
     * @param $filename
     * @param $data
     * @return csvfile
     */
    public static function exportCsvFile( $filename, $datas, $delim = "\t", $enc = 'SJIS', $output = true )
    {
        // make temporary directory
        //
        $dir = Yii::getPathOfAlias('application.runtime') . Yii::app()->params['export']['tmp_dir'];
        if( true !== file_exists( $dir ) )
        {
            mkdir( $dir, 0777, true );
        }

        // set filepath
        //
        if( false === strrpos( $filename, '.' ) )
        {
            $filename = $filename . '.csv';
        }
        $filepath = $dir . $filename;

        // create csv file
        //
        $csv = new ECSVExport( $datas );
        $csv->setToAppend();
        $csv->setDelimiter( $delim );
        $ret = $csv->toCSV( $filepath );

        // convert encoding ...
        //
        $content = file_get_contents( $filepath );
        $content = mb_convert_encoding( $content, $enc, 'UTF-8' );
        $fp = fopen( $filepath, 'w' );
        fwrite( $fp, $content );
        fclose( $fp );

        if( true === $output )
        {
            // clean temporary ...
            //
            unlink( $filepath );
            // output csv-file content
            //
            header( 'Content-type: text/csv' );
            header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
            //echo mb_convert_encoding( $filedata, 'sjis-win', mb_detect_encoding( $filedata, 'UTF-8,EUC-JP,SJIS,ASCII,JIS' ) );
            
            echo $content;
            exit;
        }

        return $filepath;
    }

	/**
     * import csv file
     *
     * @param $filename ファイル名(inputに指定した名前)
     * @param $deli デリミタ
     * @return data
     */
    public static function getCsvFile($filename = 'csv_file', $deli = "\t")
    {
        setlocale(LC_ALL, 'ja_JP.UTF-8');
		$csv_data = array();

		//uploadチェック
		if (!isset($_FILES[$filename]['tmp_name']) || !file_exists($_FILES[$filename]['tmp_name']) || ($_FILES[$filename]['error'] > 0))
        {
			return 1;
		}
        //csvチェック
		if(substr($_FILES[$filename]['name'], strrpos($_FILES[$filename]['name'], '.') + 1) != 'csv')
		{
			return 2;
		}
		
        //UTF-8にエンコード
        $file_data = file_get_contents($_FILES[$filename]['tmp_name']);
        mb_convert_variables('UTF-8', 'sjis-win,SJIS,UTF-8,EUC-JP,ASCII,JIS', $file_data);
        file_put_contents($_FILES[$filename]['tmp_name'], $file_data);
        //読み込み
        $fp = fopen($_FILES[$filename]['tmp_name'], 'r');
        if(!$fp)
        {
			return 1;
		}

		$line_count = 0;
		while(($line = fgetcsv($fp, 0, $deli, '"')) != FALSE)
		{
			//最初の行は飛ばす
			if($line_count <= 0)
			{
				$line_count = 1;
				continue;
			}
			$csv_data[$line_count] = $line;
			$line_count++;
		}
		fclose($fp);

		return $csv_data;
    }
    /**
    * Convert date which contains day of week
    * @param type $date
    * @return string
    */
    public static function logFile($fileLocation, $content) {
       
        $file = fopen($fileLocation,"w");
        fwrite($file,$content);
        fclose($file);
    }
    
    public static function setSessionSiteId($value){
        Yii::app()->session['site_id'] = $value;
    }
    public static function getSessionSiteId(){
        return Yii::app()->session['site_id'];
    }


    public function uniquename($prefix, $extension = "")
    {
        $return = '';
        for ($i = 0; $i < 7; $i++) {
            $return .= chr(rand(97, 122));
        }

        if (empty($extension))
            $return = "$prefix-$return-" . time();
        else
            $return = "$prefix-$return-" . time() . ".$extension";
        return $return;
    }

    public static function get_root_folder()
    {
        $result = dirname(dirname(dirname(__FILE__)));
        return $result;
    }

    public static  function saveTags($tagNames,$rid,$taxonomy){


        if($tagNames != null && $rid != null ){

            //used tag_ids
            $usedTagIDsArr = array();
            $relations = TagRelations::model()->findAllByAttributes(array('rid'=>$rid, 'taxonomy'=>$taxonomy));
            if($relations != null){
                foreach($relations as $rel){
                    array_push($usedTagIDsArr,$rel->tag_id);
                }
            }
            // remove all existing tags
            TagRelations::model()->deleteAllByAttributes(array('rid'=>$rid, 'taxonomy'=>$taxonomy));

            $newTagNameArr = explode(",",$tagNames);
            foreach($newTagNameArr as $tag_name){
                if($tag_name != ""){
                    $tag = Tags::model()->findByAttributes(array('name'=>$tag_name)); // find tag with name, if not exist, insert new tag

                    if($tag == null){
                        $tag = new Tags();
                        $tag->name = $tag_name;
                        $tag->abbr_cd = trim(self::removeSpecialCharsWithHyphens($tag->name));
                        $tag->created = date("Y-m:d H:m:i");
                        $tag->site_id = Helpers::getSessionSiteId();
                        $tag->used = 1;
                    }else{
                        $tag->used = (!in_array($tag->id,$usedTagIDsArr))? $tag->used+1 : $tag->used;
                    }
                    $tag->save(false);
                    // create relationship
                    $tagRelation = new TagRelations();
                    $tagRelation->tag_id = $tag->id;
                    $tagRelation->rid = $rid;
                    $tagRelation->taxonomy = $taxonomy;
                    $tagRelation->created = date("Y-m:d H:m:i");
                    $tagRelation->save(false);
                }

            }
        }
    }

    public function saveTagsTemp($tagNames,$rid,$taxonomy){


        if($tagNames != null && $rid != null ){

            //used tag_ids
            $usedTagIDsArr = array();
            $relations = TagRelationsTemp::model()->findAllByAttributes(array('rid'=>$rid, 'taxonomy'=>'contents'));
            if($relations != null){
                foreach($relations as $rel){
                    array_push($usedTagIDsArr,$rel->tag_id);
                }
            }
            // remove all existing tags
            TagRelationsTemp::model()->deleteAllByAttributes(array('rid'=>$rid, 'taxonomy'=>'contents'));

            $newTagNameArr = explode(",",$tagNames);
            foreach($newTagNameArr as $tag_name){
                if($tag_name != ""){
                    $tag = Tags::model()->findByAttributes(array('name'=>$tag_name)); // find tag with name, if not exist, insert new tag

                    if($tag == null){
                        $tag = new TagsTemp();
                        $tag->name = $tag_name;
                        $tag->abbr_cd = trim(self::removeSpecialCharsWithHyphens($tag->name));
                        $tag->created = date("Y-m:d H:m:i");
                        $tag->site_id = Helpers::getSessionSiteId();
                        $tag->used = 1;
                    }else{
                        $tag->used = (!in_array($tag->id,$usedTagIDsArr))? $tag->used+1 : $tag->used;
                    }
                    $tag->save(false);
                    // create relationship
                    $tagRelation = new TagRelationsTemp();
                    $tagRelation->tag_id = $tag->id;
                    $tagRelation->rid = $rid;
                    $tagRelation->taxonomy = $taxonomy;
                    $tagRelation->created = date("Y-m:d H:m:i");
                    $tagRelation->save(false);
                }

            }
        }
    }
    function removeSpecialCharsWithHyphens($string) {
        $string = preg_replace('/\s+/', '', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    public static function getVideoYoutube($videoId,$height,$width){
        $iFrame= "<iframe width=$width height=$height src='//www.youtube.com/embed/video_id' frameborder='0' allowfullscreen></iframe>";
        $result = str_replace("video_id", $videoId, $iFrame);
        return $result;
    }
}

?>
