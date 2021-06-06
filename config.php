<?php /**
 * Site: http://tokapps.ir
 * AuthorName: gholamreza beheshtian
 * AuthorNumber:09353466620
 * AuthorCompany: tokapps
 *
 *
 */

use system\lib\Triggers;
use yii\base\Event;
use yii\web\Application;

$dir = basename(__DIR__);


$conf =
    [
        'name' => $dir,
        'name_fa' => 'تراکنش ها',
        'type' => ['backend'],
        'namespace' => 'system\modules\\' . $dir,
        'address' => '',
        'menu' => []
        ,
    ];

$settings =
    [
        [
            'id' => 'gateOptions',
            'tabTitle' => Yii::t('transactions', 'تنظیمات پرداخت'),
        ]
    ];


// < تغییری در این بخش ندهید - این بخش را برای همه ی ماژول هایی که نیاز به تنظیمات دارند کپی کنید >
{
    Event::on(Triggers::className(), Triggers::AFTER_SETTINGS_TAB, function () use ($settings) {
        foreach ($settings as $s) {
            echo '<li>
                    <a href="#' . $s['id'] . '" data-toggle="tab">' . $s['tabTitle'] . '</a>
              </li>';
        }

    });
    Event::on(Triggers::className(), Triggers::AFTER_SETTINGS_TAB_CONTENT, function () use ($settings) {
        $model = \system\modules\setting\models\DynamicModel::getInstans();
        $form = $model::getForm();
        foreach ($settings as $s) {
            echo '<div class="tab-pane" id="' . $s['id'] . '">';
            include __DIR__ . '/settings/' . $s['id'] . '.php';
            echo '</div>';
        }
    });
}
// </ تغییری در این بخش ندهید - این بخش را برای همه ی ماژول هایی که نیاز به تنظیمات دارند کپی کنید >


if (!defined('MTHJK_' . $dir)) {
    define('MTHJK_' . $dir, '1');
}
return $conf;
