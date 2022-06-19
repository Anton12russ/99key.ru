<?php

use yii\db\Migration;

/**
 * Class m220619_175450_add_mail
 */
class m220619_175450_add_mail extends Migration
{
    public function safeUp()
    {
        $this->insert('mail', [
            'name' => 'Добавили экспресс объявление.',
			'text' => '
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
            <tr>
                <td align="center">
                    <p class="logo"><a href="https://1tu.ru/"><img src="https://1tu.ru/images_all/logo_email.png" style="display: block; height: auto; max-width: 10%; margin: auto;"> </a>
                    </p>
                    <table border="0" cellpadding="0" cellspacing="0" class="responsive-table padding30">
                    <tbody>
                    <tr>
                        <td style="text-align: center;">
                            <h2 class="title">Добавлено экспресс объявление, оно ждет модерации.</h2>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
            </table>',
        ]);
    }

    public function safeDown()
    {
        $this->delete('mail', [
            'name' => 'Добавили экспресс объявление.',
			'text' => '
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
            <tr>
                <td align="center">
                    <p class="logo"><a href="https://1tu.ru/"><img src="https://1tu.ru/images_all/logo_email.png" style="display: block; height: auto; max-width: 10%; margin: auto;"> </a>
                    </p>
                    <table border="0" cellpadding="0" cellspacing="0" class="responsive-table padding30">
                    <tbody>
                    <tr>
                        <td style="text-align: center;">
                            <h2 class="title">Добавлено экспресс объявление, оно ждет модерации.</h2>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
            </table>'
        ]);
    }
}
