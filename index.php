<?php
    $str1 = "abcdefg";
    $str2 = "abc";
    var_dump(strpos($str1, $str2));die;
    if(strpos($str1, $str2)) {
        echo "success";
    } else {
        echo "fail";
    }
    die;
//中奖奖品
$prize_arr = array(

    0=>array( 'id'=>1,'prize'=>'现金500W','v'=>1 ), //概率为1/200
    1=>array( 'id'=>2,'prize'=>'iphone7','v'=>5 ),
    2=>array( 'id'=>3,'prize'=>'耐克跑鞋','v'=>10 ),
    3=>array( 'id'=>4,'prize'=>'魔声耳机','v'=>24 ),
    4=>array( 'id'=>5,'prize'=>'蓝牙音响','v'=>60 ),
    5=>array( 'id'=>6,'prize'=>'现金1元','v'=>100 )

);


/*
 * 对数组进行处理
 */

foreach( $prize_arr as $k => $v ){
    //使用新数组item
    $item[$v['id']] = $v['v'];
}

/*
 array(
        1 => 1,
        2 => 5,
        3 => 10,
        4 => 24,
        5 => 60,
        6 => 100
     );
 */

function get_rand($item){

    $num = array_sum($item);//计算出分母200
    foreach( $item as $k => $v ){

        $rand = mt_rand(1, $num);//概率区间(整数) 包括1和200
        /*
         *这个算法很666
         */
        if( $rand <= $v ){
            //循环遍历,当下标$k = 1的时候，只有$rand = 1 才能中奖
            $result = $k;
            echo $rand.'--'.$v."\n";
            break;
        }else{
            //当下标$k=6的时候，如果$rand>100 必须$rand < = 100 才能中奖 ，那么前面5次循环之后$rand的概率区间= 200-1-5-10-24-60 （1,100） 必中1块钱
            $num-=$v;
            echo '*'.$rand.'*'."&ensp;"."&ensp;"."&ensp;"."\n";
        }
    }

    return $result;
}

$res = get_rand($item);
$prize = $prize_arr[$res-1]['prize'];
echo $prize;