<?php
return [
	[
		"label"	=> "Dashboard",
		"route"	=> "admin.dashboard",
		"icon"	=> "fas fa-home",

	],

	[
		"label"	=> "Quản lý chủ đề",
		"route"	=> "admin.analysis",
		"icon"	=> "fas fa-tasks",
		"item"	=> [
			[
			"label"	=> "Danh sách chủ đề",
			"route"	=> "admin.topics",
			"icon"	=> "far fa-list-ol",
			],
			[
			"label"	=> "Thêm nguồn",
			"route"	=> "admin.add-source",
			"icon"	=> "far fa-plus-square",
			],
			[
			"label"	=> "Thêm chủ đề",
			"route"	=> "admin.add-topic",
			"icon"	=> "far fa-plus-square",
			],
			[
			"label"	=> "Danh sách tiêu đề",
			"route"	=> "admin.titles",
			"icon"	=> "far fa-list-ol",
			],
			[
			"label"	=> "Thêm tiêu đề",
			"route"	=> "admin.add-title",
			"icon"	=> "far fa-plus-square",
			],	


		]

	],

	[
		"label"	=> "Thống kê từ",
		"route"	=> "admin.words",
		"icon"	=> "fas fa-analytics",
		"item"	=> [
			[
			"label"	=> "Quản lý từ",
			"route"	=> "admin.words",
			"icon"	=> "",
			],
			[
			"label"	=> "Thêm từ",
			"route"	=> "admin.add-words",
			"icon"	=> "",
			],
			[
			"label"	=> "Quản lý câu",
			"route"	=> "admin.sentences",
			"icon"	=> "",
			],
		]

	],

	[
		"label"	=> "Bookmark",
		"route"	=> "admin.bookmark",
		"icon"	=> "fad fa-bookmark",
		"item"	=> [
			[
			"label"	=> "Bookmark chủ đề",
			"route"	=> "admin.bookmark-topic",
			"icon"	=> "",
			],
			[
			"label"	=> "Boookmark từ",
			"route"	=> "admin.bookmark-word",
			"icon"	=> "",
			],
			// [
			// "label"	=> "Học",
			// "route"	=> "admin.learn",
			// "icon"	=> "",
			// ],
		]

	],

	[
		"label"	=> "Administrator",
		"route"	=> "admin.administrator",
		"icon"	=> "fas fa-user-cog",
		"item"	=> [
			[
			"label"	=> "Danh sách admin",
			"route"	=> "admin.administrator-list",
			"icon"	=> "",
			],
			[
			"label"	=> "Phân quyền",
			"route"	=> "admin.administrator-role",
			"icon"	=> "",
			]
		]

	],
	[
		"label"	=> "User",
		"route"	=> "admin.user",
		"icon"	=> "fa-user",

	],
	

];

?>