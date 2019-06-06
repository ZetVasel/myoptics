<?php
// шаблоны (иначе недопустимо)
Route::pattern('id', '[0-9]+');
Route::pattern('page_type', '(main|other|bay|catalog)');
Route::pattern('ord_type', '(new|taken|made)');
Route::pattern('page_url', '[0-9a-z-_]+');

Route::group(['prefix' => 'master', 'middleware' => 'auth'], function(){

	Route::get('/', 'Admin\AdminHomeController@index');
	Route::get('/products_management', 'Admin\AdminProductsController@productsManagement');
	Route::get('/feedback_management', 'Admin\AdminFeedbackController@feedbackTypes');

	// страницы
	Route::get('/pages/{page_type}', 'Admin\AdminPageController@pagesCategories');
	Route::post('/pages/{page_type}', 'Admin\AdminPageController@actionPost');
	Route::get('pages/{page_type}/edit/{id}', 'Admin\AdminPageController@editPost' );
	Route::post('pages/{page_type}/edit/{id}', 'Admin\AdminPageController@postEdit' );
	Route::get('pages/{page_type}/add', 'Admin\AdminPageController@getAdd' );
	Route::post('pages/{page_type}/add', 'Admin\AdminPageController@postAdd' );
	Route::post('slide_text', 'Admin\AdminSliderController@postSlideText');

	Route::controllers([
		'pages'					=> 'Admin\AdminPageController',
		'offers' 				=> 'Admin\AdminOffersController',
		'delivery'				=> 'Admin\AdminDeliveryController',
		'payment'				=> 'Admin\AdminPaymentController',
		'links'					=> 'Admin\AdminLinksController',
		'news'					=> 'Admin\AdminNewsController',
		'slider'					=> 'Admin\AdminSliderController',
		'feedback' 				=> 'Admin\AdminFeedbackController',
		'users'					=> 'Admin\AdminUsersController',
		'categories' 			=> 'Admin\AdminCategoriesController',
		'brands' 				=> 'Admin\AdminBrandsController',
		'products'				=> 'Admin\AdminProductsController',
		'settings' 				=> 'Admin\AdminSettingsController',
		'orders' 				=> 'Admin\AdminOrdersController',
		'subscrib' 				=> 'Admin\AdminSubscribeController',
		'features' 				=> 'Admin\AdminFeaturesController',
		'option_variants' 	=> 'Admin\AdminOptionVariantsController',
		'pointers' 		      => 'Admin\AdminPointerController',
		'notifications' 		=> 'Admin\AdminNotificationsController',
      'charlist' 				=> 'Admin\AdminCharlistController',
      'charlist_option_variants' 		=> 'Admin\AdminCharlistOptionVariantsController',
		'product_reviews' 		=> 'Admin\AdminProductReviewsController'	
	]);
});

Route::controllers([
	'auth' 				=> 'Auth\AuthController',
	'password' 			=> 'Auth\PasswordController',
   'service'         => 'Frontend\ServiceController'
]);

//поставить на cron каждый день
Route::get('/cronNotification', 'Frontend\NotificationController@index');
Route::get('/', 'Frontend\HomeController@index');

// QUICK ORDER 
Route::post('/quick_order', 'Frontend\CartController@quickOrder');

Route::get('/personal-area', 'Frontend\PersonalAreaController@index');
Route::post('/login', 'Frontend\PersonalAreaController@postLogin');

Route::get('/logout', 'Frontend\PersonalAreaController@logout');
Route::get('/edit-info', 'Frontend\PersonalAreaController@getEditInfo');
Route::post('/edit-info', 'Frontend\PersonalAreaController@postEditInfo');

Route::get('/notifications', 'Frontend\PersonalAreaController@notifications');
Route::post('/notifications', 'Frontend\PersonalAreaController@postNotifications');

Route::post('/search', 'Frontend\BaseController@search');

// CATALOG
Route::get('/category', 'Frontend\CategoriesController@getOpenCat');
Route::any('/category/{page_url}', 'Frontend\CategoriesController@getProducts');
Route::post('/change-toggle', 'Frontend\CategoriesController@changeToggle');

// PRODUCTS
Route::get('/product/{page_url}', 'Frontend\ProductController@showProduct');
Route::post('/add_review', 'Frontend\ProductController@postAddReviews');
Route::post('/update_reviews_rating', 'Frontend\ProductController@postUpdateReviewsRating');
Route::post('/add_to_favorite', 'Frontend\ProductController@postAddToFavorite');

// COMPARE
Route::get('/compare', 'Frontend\CompareController@showCompare');
Route::get('/compare/{page_url}', 'Frontend\CompareController@getCompare');
Route::post('/to_compare', 'Frontend\CompareController@addToCompare');
Route::post('/remove-from-compare', 'Frontend\CompareController@removeFromCompare');

// Подписка
Route::post('/send_sub', 'Frontend\BaseController@SubscribePost');

Route::post('/setShowProducts', 'Frontend\BaseController@setShow');

//CART
Route::post('/cartCharlist', 'Frontend\CartController@prodToCart');
Route::post('/changeQuantity', 'Frontend\CartController@changeQuantity');
Route::post('/remove_from_cart', 'Frontend\CartController@removeFromCart');
Route::post('/getCharList', 'Frontend\CartController@getCharList');
Route::post('/newFeature', 'Frontend\CartController@newFeature');

Route::post('/confirmOrder', 'Frontend\CartController@confirmOrder');
Route::post('/sendRewiew', 'Frontend\BaseController@sendRewiew');
Route::get('/cartStepTwo', 'Frontend\CartController@getCartStepTwo');
Route::post('/cartStepTwo', 'Frontend\CartController@postCartStepTwo');

//google
Route::get('/google-auth', 'Frontend\SocialsAuthController@authGoogle');
//facebook
Route::get('/facebook-auth', 'Frontend\SocialsAuthController@authFacebook');

//register
Route::get('/reg', 'Frontend\PersonalAreaController@getRegister');
Route::post('/register', 'Frontend\PersonalAreaController@postRegister');

Route::get('/cart', 'Frontend\CartController@getCart');
Route::post('/tocart', 'Frontend\CartController@toCart');
//Route::post('/change_quantity', 'Frontend\CartController@changeQuantity');
Route::post('/change_delivery', 'Frontend\CartController@changeDelivery');
Route::post('/change_payment', 'Frontend\CartController@changePayment');
Route::post('/checkout', 'Frontend\CartController@fullOrder');

//NEWS
Route::get('/news', 'Frontend\NewsController@index');
Route::get('/news/{page_url}', 'Frontend\NewsController@getNews');

//Offers
Route::get('/offers', 'Frontend\OffersController@index');
Route::get('/offers/{page_url}', 'Frontend\OffersController@getOffers');

//BRANDS
Route::get('/brand/{page_url}', 'Frontend\PageController@getBrand');
//contacs
Route::get('/contacts', 'Frontend\PageController@getContacts');

/* Profile
-----------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------*/
Route::post('/feedback', 'Frontend\BaseController@feedback');
Route::get('/{page_url}','Frontend\PageController@index');