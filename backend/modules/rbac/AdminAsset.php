<?php
namespace mdm\admin;

/**
 * AdminAsset.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 *        
 * @since 1.0
 */
class AdminAsset extends \yii\web\AssetBundle {

    /**
     *
     * {@inheritdoc}
     *
     */
    public $sourcePath = '@mdm/admin/assets';

    /**
     *
     * {@inheritdoc}
     *
     */
    public $js = [
            'yii.admin.js',
            'yii.comment.js'
    ];

    public $depends = [
            'yii\web\YiiAsset'
    ];
}
