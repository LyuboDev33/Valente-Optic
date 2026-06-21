    <header class="main-header-two">
        <div class="main-header-two__wrapper">
            <nav class="main-menu main-menu-two">
                <div class="main-menu-two__wrapper">
                    <div>
                        <div class="main-menu-two__wrapper-inner container">
                            <div class="main-menu-two__left">
                                <div class="main-header-two__logo">
                                    <x-logo width="120" />
                                </div>
                            </div>
                            <div class="main-menu-two__main-menu-box">

                                <ul class="main-menu__list">

                                    <li>
                                        <a href="/">Начало</a>
                                    </li>

                                    <li>
                                        <a href="/about">За нас</a>
                                    </li>

                                    <li class="dropdown">
                                        <a href="#">Услуги</a>
                                        <ul>
                                            <li>
                                                <a href="/service/konsultaciy-za-ochila">
                                                    Консултация за очила
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/services/diagnostics">
                                                    Компютърна диагностика
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/services/prescription-glasses">
                                                    Изработка на очила
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/services/glasses-adjustment">
                                                    Регулиране и сервиз на очила
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="dropdown">
                                        <a href="/shop">Магазин</a>
                                        <ul>
                                            <li>
                                                <a href="/shop?category=frames">
                                                    Диоптрични рамки
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/shop?category=sunglasses">
                                                    Слънчеви очила
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/shop?category=men">
                                                    Мъжки модели
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/shop?category=women">
                                                    Дамски модели
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/shop?category=kids">
                                                    Детски модели
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/shop">
                                                    Всички продукти
                                                </a>
                                            </li>
                                        </ul>
                                    </li>


                                    <li>
                                        <a href="/contact">Контакти</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="d-flex gap-3 align-items-center">
                                <a href="/cart" class="cart numb">
                                    <img src="{{ asset('/assets/images/shopping.png') }}" alt="Shopping cart">

                                    <span>
                                        {{ count(session('products', [])) }}
                                    </span>

                                </a>
                                <a href="#" class="mobile-nav__toggler">
                                    <i class="fa fa-bars"></i>
                                </a>
                                <div class="main-menu-two__right">
                                    <div class="main-menu-two__call-icon">
                                        <i class="icon-phone"></i>
                                    </div>
                                    <div class="main-menu-two__call-content">
                                        <h5 class="main-menu-two__call-number">
                                            <a href="tel:3598770000027">+359 877 000 027</a>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>


    <div class="stricky-header stricked-menu main-menu main-menu-four">
        <div class="sticky-header__content"></div>
        <!-- /.sticky-header__content -->
    </div>
    <!-- /.stricky-header -->

    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <!-- /.mobile-nav__overlay -->
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>

            <!-- /.logo-box -->
            <div class="mobile-nav__container"></div>
            <!-- /.mobile-nav__container -->

            <ul class="mobile-nav__contact list-unstyled">
                <li>
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:needhelp@packageName__.com">needhelp@crank.com</a>
                </li>
                <li>
                    <i class="fas fa-phone"></i>
                    <a href="tel:666-888-0000">666 888 0000</a>
                </li>
            </ul>
            <!-- /.mobile-nav__contact -->
            <div class="mobile-nav__top">
                <div class="mobile-nav__social">
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-facebook-square"></a>
                    <a href="#" class="fab fa-pinterest-p"></a>
                    <a href="#" class="fab fa-instagram"></a>
                </div>
                <!-- /.mobile-nav__social -->
            </div>
            <!-- /.mobile-nav__top -->
        </div>
        <!-- /.mobile-nav__content -->
    </div>
    <!-- /.mobile-nav__wrapper -->
