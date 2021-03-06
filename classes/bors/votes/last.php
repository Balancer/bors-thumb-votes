<?php

class bors_votes_last extends balancer_board_page
{
	function title() { return ec('Оценки сообщений'); }
	function config_class() { return 'airbase_board_config'; }
	function body_engine() { return 'body_php'; }
	function template() { return 'forum/_header.html'; }

	function _access_engine_def() { return \bors_access_public::class; }

	function body_data()
	{
		$last = [];
/*
		$last = objects_array('bors_votes_thumb', array(
				'create_time>' => time() - 86400,
				'order' => '-create_time',
				'group' => 'target_class_name,target_object_id',
				'limit' => 30,
		));
*/
		foreach(bors_find_all('bors_votes_thumb', array(
				'create_time>' => time() - 86400,
				'order' => '-create_time',
//				'limit' => 100,
		)) as $vote)
			if(empty($last[$idx = $vote->target_class_name().'-'.$vote->target_object_id()]))
				$last[$idx] = $vote;

		$last = array_values($last);
		$last = array_splice($last, 0, 30);

		$best = bors_find_all('bors_votes_thumb', array(
				'group' => 'target_class_name,target_object_id',
				'order' => 'SUM(score) DESC',
				'limit' => 50,
		));

		$best_of_month = bors_find_all('bors_votes_thumb', array(
				'create_time>' => time()-86400*30,
				'group' => 'target_class_name,target_object_id',
				'order' => 'SUM(score) DESC',
				'limit' => 50,
		));

		$differents = bors_find_all('bors_votes_thumb', array(
				'create_time>' => time()-86400*30,
				'group' => 'target_class_name,target_object_id',
				'order' => 'SUM(ABS(score))/(SUM(score) + 1) DESC',
				'limit' => 10,
		));

		bors_objects_targets_preload($last);
		bors_objects_targets_preload($best);
		bors_objects_targets_preload($best_of_month);
		bors_objects_targets_preload($differents);

		return array(
			'last' => $last,
			'total' => bors_count('bors_votes_thumb', array()),
			'best' => $best,
			'best_of_month' => $best_of_month,
			'differents' => $differents,
		);
	}

	function pre_show()
	{
		template_noindex();

		if(bors()->client()->is_bot())
			return go('/');

		return false;
	}

	function cache_static() { return rand(60, 120); }
}
