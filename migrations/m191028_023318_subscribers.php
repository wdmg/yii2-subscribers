<?php

use yii\db\Migration;

/**
 * Class m191028_023318_subscribers
 */
class m191028_023318_subscribers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%subscribers}}', [
            'id' => $this->primaryKey(),

            'name' => $this->string(32),
            'email' => $this->string(255),

            'list_id' => $this->integer()->null(),
            'user_id' => $this->integer()->null(),

            'unique_token' => $this->string(32)->unique(),
            'status' => $this->boolean()->defaultValue(true),

            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex(
            'idx_subscribers',
            '{{%subscribers}}',
            [
                'name',
                'email',
                'list_id',
                'user_id',
                'status',
            ]
        );

        if (!(Yii::$app->db->getTableSchema('{{%subscribers_list}}', true) === null)) {
            $this->addForeignKey(
                'fk_subscribers_to_list',
                '{{%subscribers}}',
                'list_id',
                '{{%subscribers_list}}',
                'id',
                'RESTRICT',
                'CASCADE'
            );
        }


        if (!(Yii::$app->db->getTableSchema('{{%users}}', true) === null)) {
            $this->addForeignKey(
                'fk_subscribers_to_users',
                '{{%subscribers}}',
                'user_id',
                '{{%users}}',
                'id',
                'RESTRICT',
                'CASCADE'
            );
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        if (!(Yii::$app->db->getTableSchema('{{%subscribers_list}}', true) === null)) {
            $this->dropForeignKey(
                'fk_subscribers_to_list',
                '{{%subscribers}}'
            );
        }

        if (!(Yii::$app->db->getTableSchema('{{%users}}', true) === null)) {
            $this->dropForeignKey(
                'fk_subscribers_to_users',
                '{{%subscribers}}'
            );
        }
        $this->dropIndex('idx_subscribers', '{{%subscribers}}');
        $this->truncateTable('{{%subscribers}}');
        $this->dropTable('{{%subscribers}}');
    }
}
