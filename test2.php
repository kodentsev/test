<?php
/**
 * Написать функцию которая из этого массива
 */
$data1 = [
    'parent.child.field' => 1,
    'parent.child.field2' => 2,
    'parent2.child.name' => 'test',
    'parent2.child2.name' => 'test',
    'parent2.child2.position' => 10,
    'parent3.child3.position' => 10,
];

//сделает такой и наоборот
$data = [
    'parent' => [
        'child' => [
            'field' => 1,
            'field2' => 2,
        ]
    ],
    'parent2' => [
        'child' => [
            'name' => 'test'
        ],
        'child2' => [
            'name' => 'test',
            'position' => 10
        ]
    ],
    'parent3' => [
        'child3' => [
            'position' => 10
        ]
    ],
];


function convertArray($array) {
    $resultArray = [];
    foreach ($array as $key => $value) {
        if(strpos($key, '.')!==false) {
            $chunks = array_reverse(explode('.',$key));
            $newArray = [];
            foreach ($chunks as $chunk) {
                if(empty($newArray)) {
                    $newArray[$chunk] = $value;
                } else {
                    $newArray[$chunk] = $newArray;
                    unset($newArray[$previusChunk]);
                }
                $previusChunk = $chunk;
            }
            $resultArray = array_merge_recursive($resultArray,$newArray);
        } else {
             while(!empty($value)) {
                $newElement = convertElement2Dot($value,[$key]);
                if($newElement !== NULL)
                    $resultArray[] = $newElement;
             }
        }
    }
    return $resultArray;
}

function convertElement2Dot(&$array,$dotKey) {


    if(is_array($array))
    {
        foreach($array as $key=>&$arrayElement)
        {
            if(empty($arrayElement)) {
                unset($array[$key]);
                return;
            }
            $dotKey[] = $key;

            if(is_array($arrayElement))
            {
                return get($arrayElement,$dotKey);
            }
            else
            {
                unset($array[$key]);
                return [implode('.',$dotKey)=>$arrayElement];
            }
        }
    } else {
        return [$dotKey => $array];
    }
}