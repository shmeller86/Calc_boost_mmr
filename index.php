<?php
/**
 * Created by PhpStorm.
 * User: che
 * Date: 04.06.2018
 * Time: 16:24
 *
                        start - начальный показатель MMR
                        end - конечный показатель MMR
                        Результат:

                        Итоговая цена
                        Диапазоны цен:

                        0 - 2500 : 1 RUB за 1 MMR
                        2500 - 3500 : 3 RUB за 1 MMR
                        3500 - 5500 : 5 RUB за 1 MMR
                        5500 - 7000 : 10 RUB за 1 MMR
 */

/**
 * Class Calculate
 */
Class Calculate {
    /**
     * @var int
     */
    private $tmp = 0;
    /**
     * @var array
     */
    private $data = array(
        array(
            'from' => 0,
            'to' => 2499,
            'price' => 1,
            'currency' => 'RUB'
        ),
        array(
            'from' => 2500,
            'to' => 3499,
            'price' => 3,
            'currency' => 'RUB'
        ),
        array(
            'from' => 3500,
            'to' => 5499,
            'price' => 5,
            'currency' => 'RUB'
        ),
        array(
            'from' => 5500,
            'to' => 7000,
            'price' => 10,
            'currency' => 'RUB'
        )
    );
    /**
     * @var array
     */
    private $log = array();

    /**
     * @param int $a
     * @param int $b
     * @return float
     */
    public function getTotalPrice(int $a, int $b): float {
        if ($b < $a) {
            $this->log[] =  "В минус бустим бесплатно!";
            return 0;
        }
        foreach ($this->data as $k => $v) {
            if($v['from'] <= $a && $v['to'] > $a) {
                if($b < $v['to']){
                    $this->tmp += ($b - $a) * $v['price'];
                    $this->log[] = ($b - $a) . " MMR по " . $v['price'] . " руб.\t\t Всего на " . (($b - $a) * $v['price']);
                    break;
                }
                else {
                    $this->tmp += ($v['to'] - $a) * $v['price'];
                    $this->log[] = ($v['to'] - $a) . " MMR по " . $v['price'] . " руб.\t\t Всего на " . (($v['to'] - $a) * $v['price']);
                    if (!empty($this->data[$k+1]['from'])) $this->getTotalPrice($this->data[$k+1]['from'],$b);
                    else $this->log[] =  "Попытка получить расчет более ".$this->data[$k]['to'].' MMR';
                    break;
                }
            }
        }
        return $this->tmp;
    }

    public function getDetail(): string {
        return implode("\n",$this->log);
    }
}


$calc = new Calculate();

$cash =  $calc->getTotalPrice(3400, 5200);

echo 'Итоговая сумма составила: ' . $cash . ' руб.' . PHP_EOL;
echo $calc->getDetail();


