<?php

use yii\db\Migration;

/**
 * Class m181124_153901_schema
 */
class m181124_153901_schema extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string(64)->notNull(),
            'password' => $this->string(64)->notNull(),
            'auth_key' => $this->string(64),
            'access_token' => $this->string(64),
        ]);
        $this->createIndex('users_idx_access_token', 'users', 'access_token');
        $this->insert('users', [
            'username' => 'test',
            'password' => password_hash('test', PASSWORD_BCRYPT),
        ]);
        $this->insert('users', [
            'username' => 'demo',
            'password' => password_hash('demo', PASSWORD_BCRYPT),
        ]);

        $this->createTable('items', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
        ]);
        $this->insert('items', ['name' => 'item#1', 'quantity' =>  5, 'created_at' => date('Y-m-d H:i:s')]);
        $this->insert('items', ['name' => 'item#2', 'quantity' => 10, 'created_at' => date('Y-m-d H:i:s')]);
        $this->insert('items', ['name' => 'item#3', 'quantity' => 15, 'created_at' => date('Y-m-d H:i:s')]);

        $this->createTable('rewards', [
            'id' => $this->primaryKey(),
            'type_id' => $this->smallInteger()->notNull(),
            'weight' => $this->float()->notNull(),
        ]);
        $this->insert('rewards', ['type_id' => 1, 'weight' => 1.0]);
        $this->insert('rewards', ['type_id' => 1, 'weight' => 1.0]);
        $this->insert('rewards', ['type_id' => 1, 'weight' => 1.0]);
        $this->insert('rewards', ['type_id' => 2, 'weight' => 1.0]);
        $this->insert('rewards', ['type_id' => 2, 'weight' => 1.0]);
        $this->insert('rewards', ['type_id' => 2, 'weight' => 1.0]);
        $this->insert('rewards', ['type_id' => 3, 'weight' => 1.0]);
        $this->insert('rewards', ['type_id' => 3, 'weight' => 1.0]);
        $this->insert('rewards', ['type_id' => 3, 'weight' => 1.0]);

        $this->createTable('reward_items', [
            'id' => $this->integer()->notNull()->append(' PRIMARY KEY'),
            'item_id' => $this->integer()->notNull(),
        ]);
        $this->insert('reward_items', ['id' => 1, 'item_id' => 1]);
        $this->insert('reward_items', ['id' => 2, 'item_id' => 2]);
        $this->insert('reward_items', ['id' => 3, 'item_id' => 3]);

        $this->createIndex('reward_items_idx_item_id', 'reward_items', 'item_id');
        $this->addForeignKey('reward_items_fk_reward', 'reward_items', 'id', 'rewards', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('reward_items_fk_item', 'reward_items', 'item_id', 'items', 'id', 'RESTRICT', 'RESTRICT');

        $this->createTable('reward_money', [
            'id' => $this->integer()->notNull()->append(' PRIMARY KEY'),
            'min_amount' => $this->integer()->notNull(),
            'max_amount' => $this->integer()->notNull(),
        ]);
        $this->insert('reward_money', ['id' => 4, 'min_amount' => 1000, 'max_amount' => 2000]);
        $this->insert('reward_money', ['id' => 5, 'min_amount' => 500, 'max_amount' => 999]);
        $this->insert('reward_money', ['id' => 6, 'min_amount' => 10, 'max_amount' => 499]);

        $this->addForeignKey('reward_money_fk_reward', 'reward_money', 'id', 'rewards', 'id', 'RESTRICT', 'RESTRICT');

        $this->createTable('reward_points', [
            'id' => $this->integer()->notNull()->append(' PRIMARY KEY'),
            'min_amount' => $this->integer()->notNull(),
            'max_amount' => $this->integer()->notNull(),
        ]);
        $this->insert('reward_points', ['id' => 7, 'min_amount' => 10000, 'max_amount' => 20000]);
        $this->insert('reward_points', ['id' => 8, 'min_amount' => 5000, 'max_amount' => 9999]);
        $this->insert('reward_points', ['id' => 9, 'min_amount' => 1000, 'max_amount' => 4999]);

        $this->addForeignKey('reward_points_fk_reward', 'reward_points', 'id', 'rewards', 'id', 'RESTRICT', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('reward_points');
        $this->dropTable('reward_money');
        $this->dropTable('reward_items');
        $this->dropTable('items');
        $this->dropTable('rewards');
        $this->dropTable('users');
    }
}
