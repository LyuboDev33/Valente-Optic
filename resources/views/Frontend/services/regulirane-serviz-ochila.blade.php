<x-frontend>

    @section('SEO')
        <title>Регулиране и обслужване на очила | Valente Optic</title>

        <meta name="description" content="Професионално регулиране и обслужване на очила във Valente Optic. Стягане на винтчета, изправяне на рамки, почистване, полиране и настройка за удобно носене.">
        <meta name="keywords" content="регулиране на очила, обслужване на очила, ремонт на очила, изправяне на рамки, стягане на винтчета, почистване на очила, полиране на рамки, смяна на накрайници, смяна на наносници, оптика, Valente Optic">
        <meta name="author" content="Valente Optic">
        <meta name="robots" content="index, follow">

        <link rel="canonical" href="{{ url()->current() }}">

        <meta property="og:type" content="website">
        <meta property="og:locale" content="bg_BG">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="Регулиране и обслужване на очила | Valente Optic">
        <meta property="og:description" content="Вашите очила се хлъзгат, притискат или са изкривени? Предлагаме професионално регулиране, стягане, почистване и обслужване на рамки.">
        <meta property="og:image" content="{{ asset('/assets/images/seo.png') }}">
        <meta property="og:image:secure_url" content="{{ asset('/assets/images/seo.png') }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:site_name" content="Valente Optic">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Регулиране и обслужване на очила | Valente Optic">
        <meta name="twitter:description" content="Професионално регулиране, изправяне, почистване и обслужване на очила за повече комфорт и стабилност.">
        <meta name="twitter:image" content="{{ asset('/assets/images/seo.png') }}">
    @endsection

    <!--Service Details Start-->
    <section class="service-details">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="service-details__left">
                        <h3 class="service-details__title-1">
                            Регулиране и обслужване на очила
                        </h3>

                        <p class="service-details__text-1">
                            Правилното регулиране на очилата е ключово за комфортното и безопасно носене.
                            С времето рамките се разхлабват, изкривяват или просто спират да стоят стабилно.
                            Нашата услуга Регулиране и обслужване на очила гарантира, че вашите очила ще бъдат
                            отново удобни, прецизни и надеждни.
                        </p>

                        <div class="service-details__img">
                            <img src="/assets/images/resources/service-details-img-1.jpg"
                                alt="Регулиране и обслужване на очила" />
                        </div>

                        <h3 class="service-details__title-2">
                            Какво включва услугата
                        </h3>

                        <p class="service-details__text-2">
                            Услугата включва професионално регулиране на рамките за перфектно прилягане към лицето,
                            стягане и подмяна на винтчета и дребни елементи, както и изправяне на рамки при изкривяване.
                        </p>

                        <p class="service-details__text-3">
                            Извършваме почистване и полиране на рамки и накрайници, както и проверка на стабилността
                            и правилното разположение на лещите.
                        </p>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="service-details__single">
                                    <div class="service-details__icon-and-title">
                                        <div class="service-details__icon">
                                            <span class="icon-broken-car"></span>
                                        </div>
                                        <h4 class="service-details__single-title">
                                            Регулиране на рамки
                                        </h4>
                                    </div>
                                    <p>
                                        Настройка на рамката за стабилно, удобно и правилно прилягане към лицето.
                                    </p>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="service-details__single">
                                    <div class="service-details__icon-and-title">
                                        <div class="service-details__icon">
                                            <span class="icon-wheel-2"></span>
                                        </div>
                                        <h4 class="service-details__single-title">
                                            Обслужване на очила
                                        </h4>
                                    </div>
                                    <p>
                                        Стягане на винтчета, подмяна на дребни елементи, почистване и проверка.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <p class="service-details__text-4">
                            Добре регулираните очила стоят стабилно, не се хлъзгат, не причиняват дискомфорт
                            и осигуряват правилна оптична ос за по-добро зрение.
                        </p>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="service-details__right">
                        <div class="service-details__service-list-box">
                            <h3 class="service-details__service-title">
                                Услуги
                            </h3>
                            <ul class="service-details__service-list list-unstyled">
                                <li class="active">
                                    <a href="#">
                                        Регулиране на очила<span>01</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Изправяне на рамки<span>02</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Стягане на винтчета<span>03</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Почистване и полиране<span>04</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Смяна на накрайници<span>05</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="service-details__contact-box">
                            <div
                                class="service-details__contact-box-bg"
                                style="background-image: url(assets/images/backgrounds/service-details-contact-box-bg.jpg);">
                            </div>

                            <div
                                class="service-details__contact-box-bg-shape"
                                style="background-image: url(assets/images/shapes/service-details-contact-box-bg-shape.png);">
                            </div>

                            <h3 class="service-details__contact-title">
                                Нуждаете се от съдействие?
                            </h3>

                            <div class="service-details__contact-icon">
                                <span class="icon-phone"></span>
                            </div>

                            <p class="service-details__contact-sub-title">
                                Свържете се с нас
                            </p>

                            <a href="tel:0894938614" class="service-details__contact-number">
                                0894 938 614
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="service-details__bottom">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="service-details__bottom-left">
                            <div class="service-details__bottom-img">
                                <img src="/assets/images/resources/service-details-bottom-img.jpg"
                                    alt="Професионално обслужване на очила" />
                            </div>

                            <h3 class="service-details__bottom-title">
                                Защо е важно
                            </h3>

                            <p class="service-details__bottom-text">
                                Добре регулираните очила стоят стабилно и не се хлъзгат, не причиняват дискомфорт,
                                болка или отпечатъци, осигуряват правилна оптична ос и удължават живота на рамките.
                            </p>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="service-details__bottom-right">
                            <div class="service-details__bottom-points-box">
                                <ul class="service-details__bottom-points list-unstyled">
                                    <li>
                                        <div class="icon">
                                            <span class="icon-double-arrow-right"></span>
                                        </div>
                                        <p>Професионално регулиране за перфектно прилягане</p>
                                    </li>

                                    <li>
                                        <div class="icon">
                                            <span class="icon-double-arrow-right"></span>
                                        </div>
                                        <p>Стягане и подмяна на винтчета и дребни елементи</p>
                                    </li>

                                    <li>
                                        <div class="icon">
                                            <span class="icon-double-arrow-right"></span>
                                        </div>
                                        <p>Изправяне на рамки при изкривяване</p>
                                    </li>

                                    <li>
                                        <div class="icon">
                                            <span class="icon-double-arrow-right"></span>
                                        </div>
                                        <p>Почистване и полиране на рамки и накрайници</p>
                                    </li>
                                </ul>

                                <ul class="service-details__bottom-points list-unstyled">
                                    <li>
                                        <div class="icon">
                                            <span class="icon-double-arrow-right"></span>
                                        </div>
                                        <p>Когато очилата падат или се хлъзгат</p>
                                    </li>

                                    <li>
                                        <div class="icon">
                                            <span class="icon-double-arrow-right"></span>
                                        </div>
                                        <p>Когато притискат или убиват</p>
                                    </li>

                                    <li>
                                        <div class="icon">
                                            <span class="icon-double-arrow-right"></span>
                                        </div>
                                        <p>След удар, падане или изкривяване</p>
                                    </li>

                                    <li>
                                        <div class="icon">
                                            <span class="icon-double-arrow-right"></span>
                                        </div>
                                        <p>При смяна на накрайници или наносници</p>
                                    </li>
                                </ul>
                            </div>

                            <div class="service-details__bottom-img-two">
                                <img src="/assets/images/resources/service-details-bottom-img-1.jpg"
                                    alt="Регулиране на рамки" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!--Service Details End-->

</x-frontend>
