<?php

use yii\db\Migration;

/**
 * Class m191028_021845_subscribers_list
 */
class m191028_021845_subscribers_list extends Migration
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

        $this->createTable('{{%subscribers_list}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'description' => $this->text(),
            'status' => $this->boolean()->defaultValue(true),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(11)->null(),
            'updated_at' => $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex(
            'idx_subscribers_list',
            '{{%subscribers_list}}',
            [
                'title',
                'status',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_subscribers_list', '{{%subscribers_list}}');
        $this->truncateTable('{{%subscribers_list}}');
        $this->dropTable('{{%subscribers_list}}');
    }
}
