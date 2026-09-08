<?php
/**
 * Title: صفحهٔ اصلی ویرایشی دلنوازان
 * Slug: delnavazan-theme/homepage-editorial
 * Categories: delnavazan, featured
 * Keywords: homepage, persian, music, academy
 * Description: ساختار کامل صفحهٔ اصلی دلنوازان با بلوک‌های هستهٔ وردپرس.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"tagName":"section","align":"full","className":"dzn-home-section dzn-home-hero","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull dzn-home-section dzn-home-hero">
	<!-- wp:group {"align":"wide","className":"dzn-home-hero__grid","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide dzn-home-hero__grid">
		<!-- wp:group {"className":"dzn-home-hero__copy","layout":{"type":"default"}} -->
		<div class="wp-block-group dzn-home-hero__copy">
			<!-- wp:paragraph {"className":"dzn-eyebrow"} -->
			<p class="dzn-eyebrow">آکادمی آنلاین موسیقی ایرانی</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1,"className":"dzn-home-hero__title"} -->
			<h1 class="wp-block-heading dzn-home-hero__title">موسیقی ایرانی؛<br>نزدیک‌تر از همیشه</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"dzn-home-hero__lead"} -->
			<p class="dzn-home-hero__lead">دلنوازان، مسیر یادگیری آنلاین ساز و آواز ایرانی برای فارسی‌زبانانِ خارج از ایران است؛ با آموزش زنده، برنامه‌ای روشن و همراهی مدرس.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"className":"dzn-home-hero__actions"} -->
			<div class="wp-block-buttons dzn-home-hero__actions">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/enrol/' ) ); ?>">شروع ثبت‌نام</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#courses">دیدن سازها و دوره‌ها</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

			<!-- wp:paragraph {"className":"dzn-home-hero__note"} -->
			<p class="dzn-home-hero__note">آغاز مسیر با جلسهٔ معارفهٔ رایگان</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"dzn-home-hero__art"} -->
		<figure class="wp-block-image size-full dzn-home-hero__art"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/hero-strings.svg' ) ); ?>" alt=""></figure>
		<!-- /wp:image -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"dzn-home-section dzn-facts","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull dzn-home-section dzn-facts" aria-label="اطلاعات اصلی دوره‌ها">
	<!-- wp:group {"align":"wide","className":"dzn-facts__grid","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide dzn-facts__grid">
		<!-- wp:group {"className":"dzn-fact","layout":{"type":"default"}} -->
		<div class="wp-block-group dzn-fact"><!-- wp:paragraph {"className":"dzn-fact__value"} --><p class="dzn-fact__value">۱۵</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"dzn-fact__label"} --><p class="dzn-fact__label">ساز و مسیر آموزشی</p><!-- /wp:paragraph --></div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"dzn-fact","layout":{"type":"default"}} -->
		<div class="wp-block-group dzn-fact"><!-- wp:paragraph {"className":"dzn-fact__value"} --><p class="dzn-fact__value">۱۲</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"dzn-fact__label"} --><p class="dzn-fact__label">جلسهٔ آموزشی در هر ترم</p><!-- /wp:paragraph --></div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"dzn-fact","layout":{"type":"default"}} -->
		<div class="wp-block-group dzn-fact"><!-- wp:paragraph {"className":"dzn-fact__value"} --><p class="dzn-fact__value">۳۰</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"dzn-fact__label"} --><p class="dzn-fact__label">دقیقه برای هر جلسه</p><!-- /wp:paragraph --></div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"dzn-fact","layout":{"type":"default"}} -->
		<div class="wp-block-group dzn-fact"><!-- wp:paragraph {"className":"dzn-fact__value"} --><p class="dzn-fact__value">۳ ماه</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"dzn-fact__label"} --><p class="dzn-fact__label">یک مسیر منظم هفتگی</p><!-- /wp:paragraph --></div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"dzn-home-section dzn-home-why","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull dzn-home-section dzn-home-why">
	<!-- wp:group {"align":"wide","className":"dzn-section-heading","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide dzn-section-heading"><!-- wp:paragraph {"className":"dzn-eyebrow"} --><p class="dzn-eyebrow">چرا دلنوازان</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">فاصله، مانعِ ادامهٔ موسیقی نیست</h2><!-- /wp:heading --><!-- wp:paragraph --><p>برای هنرجویی که بیرون از ایران زندگی می‌کند، دسترسی به آموزش فارسی و پیوستهٔ موسیقی ایرانی همیشه ساده نیست. دلنوازان این فاصله را با کلاس زنده و مسیر آموزشی منظم کوتاه می‌کند.</p><!-- /wp:paragraph --></div>
	<!-- /wp:group -->

	<!-- wp:group {"align":"wide","className":"dzn-principles","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide dzn-principles">
		<!-- wp:group {"className":"dzn-principle","layout":{"type":"default"}} --><div class="wp-block-group dzn-principle"><!-- wp:paragraph {"className":"dzn-principle__number"} --><p class="dzn-principle__number">۰۱</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">آموزش به زبان فارسی</h3><!-- /wp:heading --><!-- wp:paragraph --><p>گفت‌وگوی دقیق و طبیعی با مدرس، بدون فاصلهٔ زبانی در مفاهیم موسیقی.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
		<!-- wp:group {"className":"dzn-principle","layout":{"type":"default"}} --><div class="wp-block-group dzn-principle"><!-- wp:paragraph {"className":"dzn-principle__number"} --><p class="dzn-principle__number">۰۲</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">کلاس زنده و فردی</h3><!-- /wp:heading --><!-- wp:paragraph --><p>زمان کلاس برای شنیدن، تمرین و بازخورد مستقیم در اختیار مسیر هنرجوست.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
		<!-- wp:group {"className":"dzn-principle","layout":{"type":"default"}} --><div class="wp-block-group dzn-principle"><!-- wp:paragraph {"className":"dzn-principle__number"} --><p class="dzn-principle__number">۰۳</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">ساختار روشن دوره</h3><!-- /wp:heading --><!-- wp:paragraph --><p>از معارفه تا ترم دوازده‌جلسه‌ای، گام بعدی و هزینهٔ مسیر از ابتدا روشن است.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"courses","align":"full","className":"dzn-home-section dzn-courses","layout":{"type":"constrained"}} -->
<section id="courses" class="wp-block-group alignfull dzn-home-section dzn-courses">
	<!-- wp:group {"align":"wide","className":"dzn-courses__grid","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide dzn-courses__grid">
		<!-- wp:group {"className":"dzn-section-heading","layout":{"type":"default"}} --><div class="wp-block-group dzn-section-heading"><!-- wp:paragraph {"className":"dzn-eyebrow"} --><p class="dzn-eyebrow">سازها و دوره‌ها</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">از ردیف و آواز تا سازهای کلاسیک</h2><!-- /wp:heading --><!-- wp:paragraph --><p>مسیر مورد علاقه‌تان را پیدا کنید. وضعیت نهایی ارائهٔ هر دوره پیش از انتشار تولیدی تأیید می‌شود.</p><!-- /wp:paragraph --></div><!-- /wp:group -->

		<!-- wp:list {"className":"dzn-course-index"} -->
		<ul class="dzn-course-index"><li>تار</li><li>سه‌تار</li><li>دوتار</li><li>سنتور</li><li>کمانچه</li><li>دف</li><li>تنبک</li><li>نی</li><li>عود</li><li>قانون</li><li>آواز ایرانی</li><li>پیانو</li><li>ویولن</li><li>گیتار</li><li>ساکسوفون</li></ul>
		<!-- /wp:list -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"dzn-home-section dzn-pricing","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull dzn-home-section dzn-pricing">
	<!-- wp:group {"align":"wide","className":"dzn-pricing__grid","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide dzn-pricing__grid">
		<!-- wp:group {"className":"dzn-pricing__intro","layout":{"type":"default"}} --><div class="wp-block-group dzn-pricing__intro"><!-- wp:paragraph {"className":"dzn-eyebrow dzn-eyebrow--light"} --><p class="dzn-eyebrow dzn-eyebrow--light">هزینه و ساختار دوره</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">روشن، منظم، بدون جست‌وجوی اضافه</h2><!-- /wp:heading --><!-- wp:paragraph --><p>پیش از ثبت‌نام بدانید یک ترم چگونه می‌گذرد و چه زمانی پرداخت انجام می‌شود.</p><!-- /wp:paragraph --></div><!-- /wp:group -->

		<!-- wp:group {"className":"dzn-price-ledger","layout":{"type":"default"}} -->
		<div class="wp-block-group dzn-price-ledger">
			<!-- wp:group {"className":"dzn-price-ledger__feature","layout":{"type":"default"}} --><div class="wp-block-group dzn-price-ledger__feature"><!-- wp:paragraph {"className":"dzn-price-ledger__label"} --><p class="dzn-price-ledger__label">هزینهٔ یک ترم</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"dzn-price-ledger__price"} --><p class="dzn-price-ledger__price"><bdi>۲۵۰</bdi> دلار استرالیا</p><!-- /wp:paragraph --></div><!-- /wp:group -->
			<!-- wp:group {"className":"dzn-price-ledger__row","layout":{"type":"default"}} --><div class="wp-block-group dzn-price-ledger__row"><!-- wp:paragraph --><p>مدت ترم</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>۳ ماه</p><!-- /wp:paragraph --></div><!-- /wp:group -->
			<!-- wp:group {"className":"dzn-price-ledger__row","layout":{"type":"default"}} --><div class="wp-block-group dzn-price-ledger__row"><!-- wp:paragraph --><p>جلسهٔ آموزشی</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>۱۲ جلسه</p><!-- /wp:paragraph --></div><!-- /wp:group -->
			<!-- wp:group {"className":"dzn-price-ledger__row","layout":{"type":"default"}} --><div class="wp-block-group dzn-price-ledger__row"><!-- wp:paragraph --><p>برنامهٔ کلاس</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>هفته‌ای یک جلسهٔ ۳۰ دقیقه‌ای</p><!-- /wp:paragraph --></div><!-- /wp:group -->
			<!-- wp:group {"className":"dzn-price-ledger__row","layout":{"type":"default"}} --><div class="wp-block-group dzn-price-ledger__row"><!-- wp:paragraph --><p>جلسهٔ معارفه</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>رایگان و جدا از ۱۲ جلسهٔ آموزشی</p><!-- /wp:paragraph --></div><!-- /wp:group -->
			<!-- wp:paragraph {"className":"dzn-price-ledger__note"} --><p class="dzn-price-ledger__note">پرداخت پس از نخستین جلسهٔ آموزشی انجام می‌شود.</p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"dzn-home-section dzn-process","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull dzn-home-section dzn-process">
	<!-- wp:group {"align":"wide","className":"dzn-section-heading","layout":{"type":"default"}} --><div class="wp-block-group alignwide dzn-section-heading"><!-- wp:paragraph {"className":"dzn-eyebrow"} --><p class="dzn-eyebrow">دلنوازان چگونه کار می‌کند؟</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">چهار گام تا یک مسیر منظم</h2><!-- /wp:heading --></div><!-- /wp:group -->
	<!-- wp:list {"ordered":true,"align":"wide","className":"dzn-process__list"} -->
	<ol class="dzn-process__list alignwide"><li><strong>ثبت‌نام اولیه</strong><span>ساز، تجربه و شرایط زمانی خود را معرفی می‌کنید.</span></li><li><strong>جلسهٔ معارفهٔ رایگان</strong><span>پیش از شروع دوره با مسیر کلاس آشنا می‌شوید.</span></li><li><strong>نخستین جلسهٔ آموزشی</strong><span>کلاس واقعی آغاز می‌شود و تجربهٔ همکاری شکل می‌گیرد.</span></li><li><strong>ادامه در یک ترم منظم</strong><span>دوازده جلسهٔ هفتگی، مسیر تمرین و پیشرفت را می‌سازد.</span></li></ol>
	<!-- /wp:list -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"dzn-home-section dzn-trust","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull dzn-home-section dzn-trust">
	<!-- wp:group {"align":"wide","className":"dzn-trust__grid","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide dzn-trust__grid">
		<!-- wp:group {"className":"dzn-trust__art","layout":{"type":"default"}} --><div class="wp-block-group dzn-trust__art" aria-hidden="true"><!-- wp:paragraph --><p>گوش‌دادن</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>تمرین</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>گفت‌وگو</p><!-- /wp:paragraph --></div><!-- /wp:group -->
		<!-- wp:group {"className":"dzn-section-heading","layout":{"type":"default"}} --><div class="wp-block-group dzn-section-heading"><!-- wp:paragraph {"className":"dzn-eyebrow"} --><p class="dzn-eyebrow">اعتماد انسانی</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">آموزش موسیقی، رابطه‌ای انسانی است</h2><!-- /wp:heading --><!-- wp:paragraph --><p>کیفیت این مسیر فقط به تماس تصویری وابسته نیست؛ شنیدن دقیق، بازخورد روشن و پیوستگی تمرین، کلاس را زنده نگه می‌دهد.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>جلسهٔ معارفه فرصت می‌دهد پیش از ورود به ترم، با فضای آموزش و ادامهٔ مسیر آشنا شوید.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"dzn-home-section dzn-editorial","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull dzn-home-section dzn-editorial">
	<!-- wp:group {"align":"wide","className":"dzn-section-heading dzn-section-heading--row","layout":{"type":"default"}} --><div class="wp-block-group alignwide dzn-section-heading dzn-section-heading--row"><div><!-- wp:paragraph {"className":"dzn-eyebrow"} --><p class="dzn-eyebrow">مجلهٔ دلنوازان</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">برای شنیدن و شناختن</h2><!-- /wp:heading --></div><!-- wp:paragraph {"className":"dzn-inline-link"} --><p class="dzn-inline-link"><a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>">همهٔ مقاله‌ها <span aria-hidden="true">←</span></a></p><!-- /wp:paragraph --></div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":4,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"align":"wide","className":"dzn-article-query"} -->
	<div class="wp-block-query alignwide dzn-article-query"><!-- wp:post-template {"className":"dzn-article-grid"} -->
		<!-- wp:group {"tagName":"article","className":"dzn-article-card","layout":{"type":"default"}} -->
		<article class="wp-block-group dzn-article-card"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /--><!-- wp:post-date {"className":"dzn-article-card__date"} /--><!-- wp:post-title {"isLink":true,"level":3} /--><!-- wp:post-excerpt {"moreText":"خواندن مقاله"} /--></article>
		<!-- /wp:group -->
	<!-- /wp:post-template --></div>
	<!-- /wp:query -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"faq","align":"full","className":"dzn-home-section dzn-faq","layout":{"type":"constrained"}} -->
<section id="faq" class="wp-block-group alignfull dzn-home-section dzn-faq">
	<!-- wp:group {"align":"wide","className":"dzn-faq__grid","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide dzn-faq__grid">
		<!-- wp:group {"className":"dzn-section-heading","layout":{"type":"default"}} --><div class="wp-block-group dzn-section-heading"><!-- wp:paragraph {"className":"dzn-eyebrow"} --><p class="dzn-eyebrow">پرسش‌های متداول</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">پیش از شروع</h2><!-- /wp:heading --><!-- wp:paragraph --><p>پاسخ کوتاه به سؤال‌هایی که بیشتر هنرجویان پیش از ثبت‌نام دارند.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
		<!-- wp:group {"className":"dzn-faq__list","layout":{"type":"default"}} -->
		<div class="wp-block-group dzn-faq__list">
			<!-- wp:details --><details class="wp-block-details"><summary>کلاس‌ها برای چه کشورهایی برگزار می‌شود؟</summary><!-- wp:paragraph --><p>کلاس‌های آنلاین برای فارسی‌زبانان خارج از ایران طراحی شده است. زمان نهایی با توجه به منطقهٔ زمانی هنرجو هماهنگ می‌شود.</p><!-- /wp:paragraph --></details><!-- /wp:details -->
			<!-- wp:details --><details class="wp-block-details"><summary>اگر هنوز ساز ندارم چه کنم؟</summary><!-- wp:paragraph --><p>در ثبت‌نام اولیه این موضوع را مطرح کنید تا پیش از شروع کلاس، راهنمایی متناسب با ساز انتخابی دریافت کنید.</p><!-- /wp:paragraph --></details><!-- /wp:details -->
			<!-- wp:details --><details class="wp-block-details"><summary>آیا می‌توانم از سطح کاملاً مبتدی شروع کنم؟</summary><!-- wp:paragraph --><p>بله. تجربهٔ قبلی خود را در ثبت‌نام ذکر کنید تا مسیر آغاز کلاس متناسب با سطح شما روشن شود.</p><!-- /wp:paragraph --></details><!-- /wp:details -->
			<!-- wp:details --><details class="wp-block-details"><summary>اگر زمان یک جلسه مناسب نباشد چه می‌شود؟</summary><!-- wp:paragraph --><p>شرایط زمانی خود را از ابتدا اعلام کنید. جزئیات جابه‌جایی یا هماهنگی جلسه بر اساس سیاست جاری دلنوازان مشخص می‌شود.</p><!-- /wp:paragraph --></details><!-- /wp:details -->
			<!-- wp:details --><details class="wp-block-details"><summary>آیا داخل ایران هم می‌توان ثبت‌نام کرد؟</summary><!-- wp:paragraph --><p>در حال حاضر خدمات دلنوازان برای هنرجویان خارج از ایران ارائه می‌شود.</p><!-- /wp:paragraph --></details><!-- /wp:details -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"dzn-home-section dzn-final-cta","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull dzn-home-section dzn-final-cta">
	<!-- wp:group {"align":"wide","className":"dzn-final-cta__inner","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide dzn-final-cta__inner">
		<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"dzn-eyebrow dzn-eyebrow--light"} --><p class="dzn-eyebrow dzn-eyebrow--light">آماده‌اید شروع کنید؟</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">سازتان را انتخاب کنید؛ ادامهٔ راه را با هم می‌سازیم.</h2><!-- /wp:heading --><!-- wp:paragraph --><p>ثبت‌نام اولیه کوتاه است و آغاز مسیر با یک جلسهٔ معارفهٔ رایگان خواهد بود.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
		<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-dzn-light"} --><div class="wp-block-button is-style-dzn-light"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/enrol/' ) ); ?>">ثبت‌نام در دلنوازان</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
