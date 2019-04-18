<?php
/**
 * Response filter
 *
 * @Author     : pb@likingfit.com
 * @CreateTime 2018/7/23 11:13:28
 */

namespace open\behaviors;

class ResponseFilter extends \open\filters\ResponseFilter {
    public $successCode = 1;
    public $successMsg  = 'success';
    
    public function beforeSend($response) {
        $data = $response->data;

        if (isset($data['status']) && isset($data['message'])) {
            return;
        }
        
        $response->data = [
            'status' => $this->successCode,
            'message' => $this->successMsg,
            'response' => is_array($data) ? $data : (object)[]
        ];
    }
}