<?php

class VKapi extends CComponent
{

    public $apiSecret;
    public $apiId;

    function init()
    {
        $this->apiSecret =  Yii::app()->params['apiSecret'];
		$this->apiId     =  Yii::app()->params['apiId'];
    }

	function getProfiles ($uids)
	{
		$request['fields'] = 'uid,first_name,last_name,nickname,sex,bdate(birthdate),city,country,timezone,photo,photo_medium,photo_big';
		$request['uids']   = $uids;
		$request['method'] = 'secure.getProfiles';
		return $this->request($request);
	}

    /**
     * Возвращает массив профилей с ключем по uid
     *
     * это необходимо для списков в которых могут присутствовать записи с одинаковыми uid, например Activity
     *
     * @param array $uids or number $uid
     *
     * @return массив вида m[uid][name] or m[uid][photo] ...
     */
    function getPreparedProfiles ($uidsArray)
    {
        if(count($uidsArray)){
            if(is_numeric($uidsArray)){
                $uidsStr = $uidsArray;
            }else{
                $uidsStr = implode(',', $uidsArray);
            }
            $response = $this->getProfiles($uidsStr);
            if(isset($response['response'])){
                foreach($response['response'] as $item){
                    $output[$item['user']['uid']] = array('first_name' => $item['user']['first_name'],
                        'last_name' => $item['user']['last_name'],
                        'photo' => $item['user']['photo'],
                        'photo_big' => $item['user']['photo_big'],
                        'photo_medium' => $item['user']['photo_medium']);
                }
            }else{
                $output['Error'] = 'Error';
            }
        }else{
            $output['Error'] = 'Input array is empty';
        }
        return $output;
    }

	function sendNotification ($uids, $message)
	{
		$request['uids']    = $uids;
		$request['message'] = iconv('windows-1251', 'utf-8', $message);
		$request['method']  = 'secure.sendNotification';
		return $this->request($request);
	}

	function saveAppStatus ($uid, $status)
	{
		$request['uid']    = $uid;
		$request['status'] = iconv('windows-1251', 'utf-8', $status);
		$request['method'] = 'secure.saveAppStatus';
		return $this->request($request);
	}

	function getAppStatus ($uid)
	{
		$request['uid']    = $uid;
		$request['method'] = 'secure.getAppStatus';
		return $this->request($request);
	}

	function getAppBalance ()
	{
		$request['method'] = 'secure.getAppBalance';
		return $this->request($request);
	}

	function getBalance ($uid)
	{
		$request['uid']    = $uid;
		$request['method'] = 'secure.getBalance';
		return $this->request($request);
	}

	function addVotes ($uid, $votes)
	{
		$request['uid']    = $uid;
		$request['votes']  = $votes;
		$request['method'] = 'secure.addVotes';
		return $this->request($request);
	}

	function withdrawVotes ($uid, $votes)
	{
		$request['uid']    = $uid;
		$request['votes']  = $votes;
		$request['method'] = 'secure.withdrawVotes';
		return $this->request($request);
	}

	function transferVotes ($uid_from, $uid_to, $votes)
	{
		$request['uid_from'] = $uid_from;
		$request['uid_to']   = $uid_to;
		$request['votes']    = $votes;
		$request['method']   = 'secure.transferVotes';
		return $this->request($request);
	}

	function getTransactionsHistory ()
	{
		$request['method'] = 'secure.getTransactionsHistory';
		return $this->request($request);
	}

	function addRating ($uid, $rate)
	{
		$request['uid']    = $uid;
		$request['rate']   = $rate;
		$request['method'] = 'secure.addRating';
		return $this->request($request);
	}

	function setCounter ($uid, $counter)
	{
		$request['uid']     = $uid;
		$request['counter'] = $counter;
		$request['method']  = 'secure.setCounter';
		return $this->request($request);
	}

	function request($request){
		$request['random']    = rand(100000,999999);
		$request['timestamp'] = time();
		$request['format']    = 'JSON';
		$request['api_id']    = $this->apiId;

		ksort($request);

        $str='';
		foreach ($request as $key=>$value) {
			$str.=trim($key)."=".trim($value);
		}

		$request['sig'] = md5(trim($str.$this->apiSecret));

		$q = http_build_query($request);
		$result = json_decode(file_get_contents("http://api.vk.com/api.php?".$q),TRUE);

		return $result;
	}
}