@extends('frontend.layout.app')
@section('title','Home')
@section('body')
    <!-- banner-section -->
    <section class="banner-section p_relative">
        <div class="container-fluid bg-white">
            <div class="row align-items-stretch flex-md-row flex-column-reverse">
                <!-- Left Column (Form) -->
                <div class="col-12 col-lg-5 px-2 d-flex order-lg-1 order-md-2">
                    <div class="card callback-box w-100 d-flex h-100 shadow-lg rounded-3 border-0">
                        <div class="card-body d-flex flex-column justify-content-center p-lg-4 p-0">
                            <!-- বড় শিরোনাম -->
                            <h1 class="text-white mb-4 lh-sm" style="font-size: clamp(35px, 20vw, 40px); font-style: inherit">
                                Get a Call Back
                            </h1>
                            <h1 class="text-white mb-4 lh-sm" style="font-family: 'Exo-Black', sans-serif; font-weight: 900;">In <span class="text-theme display-5">30</span> Seconds</h1>
                            <form action="{{route('get.call.back.store')}}" method="post">
                            @csrf
                            <!-- Name input -->
                                <div class="input-row mb-3">
                                    <label class="form-label fw-semibold fs-5 pe-3 text-white">Name * :</label>
                                    <input type="text"
                                           class="form-control-plaintext bg-transparent text-white rounded-0"
                                           placeholder="Type your full name" name="name">
                                </div>

                                <!-- Phone input -->
                                <div class="input-row mb-3">
                                    <label class="form-label fw-semibold fs-5 pe-3 text-white">Cell No * :</label>
                                    <input type="tel"
                                           class="form-control-plaintext bg-transparent text-white rounded-0"
                                           placeholder="+88" name="cell_no">
                                </div>

                                <!-- Button -->
                                <div class="mt-4 text-end">
                                    <button class="btn btn-primary btn-lg fw-bold px-4">
                                        Send Us
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Banner Carousel) -->
                <div class="col-12 col-lg-7 d-flex mb-3 mb-md-0 order-lg-2 order-md-1 px-0">
                    <div id="bannerCarousel" class="carousel slide w-100 h-100" data-bs-ride="carousel">
                        <div class="carousel-inner rounded-lg-4 rounded-1 h-100">
                            <!-- Slide 1 -->
                            @foreach($sliders as $key => $slider)
                                <div class="carousel-item {{ $key == 0 ? 'active' : ''}}  h-100">
                                    <img src="{{asset($slider->image)}}" class="d-block w-100 h-100 object-fit-cover" alt="Banner 1">
                                </div>
                            @endforeach
                        </div>

                        <!-- Indicators -->
                        <div class="carousel-indicators position-absolute" style="bottom: -10px;">
                            @foreach($sliders as $key => $slider)
                            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{$key}}" class=" {{ $key == 0 ? 'active' : ''}} rounded-circle"></button>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- banner-section end -->

    {{-- Quick supports icons --}}
    <section class="py-5 bg-white">
        <div class="container-fluid">
            <div class="row justify-content-center g-4 text-end">
                <div class="col-11">
                    <div class="row">
                        @foreach($quickSupports as $quickSupport)
                            <div class="col-6 col-sm-6 col-lg-3 my-2">
                                <div class="row h-100 align-items-center">
                                    <div class="col-8">
                                        <div class="" style="padding-right:0; font-size: clamp(20px, 20vw, 20px); font-style: normal; font-family: 'Arial',sans-serif">
                                            {{$quickSupport->name}}
                                        </div>
                                    </div>
                                    <div class="col-4 p-0 m-0 text-start">
                                        <img src="{{asset($quickSupport->icon)}}" alt="" class="img-fluid w-100" style="object-fit: cover">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- about-section -->
    <section class="about-section pt_90 pb_120" id="aboutSection">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                    <div class="image_block_one">
                        <div class="image-box pr_90">
                            <figure class="image image-hov-one"><img src="{{asset('/')}}frontend/assets/images/resource/about-1.jpg" alt=""></figure>
                            <div class="rotate-box">
                                <span class="curved-circle">30 Years Experience&nbsp;&nbsp;-&nbsp;&nbsp;30 Years Experience&nbsp;&nbsp;-&nbsp;&nbsp;</span>
                                <div class="icon-box">
                                    <img src="{{asset('/')}}frontend/assets/images/favicon.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                    <div class="content_block_one">
                        <div class="content-box ml_50">
                            <div class="sec-title mb_30">
                                <span class="sub-title mb_12 fs-6">Who We are</span>
                                <h2 class="fw-bold" style="font-size: 60px">Excellence in Emergency Medical Services</h2>
                            </div>
                            <div class="tabs-box">
                                <ul class="nav nav-pills" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active m-1 py-3 fs-3 rounded-4" style="padding-right: 90px; padding-left: 90px; border: 2px solid #0d5c37;" id="pill-tab-0" data-bs-toggle="pill" href="#pill-tabpanel-0" role="tab" aria-controls="pill-tabpanel-0" aria-selected="true">Mission</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link m-1 py-3 fs-3 rounded-4" style="padding-right: 90px; padding-left: 90px; border: 2px solid #0d5c37;" id="pill-tab-1" data-bs-toggle="pill" href="#pill-tabpanel-1" role="tab" aria-controls="pill-tabpanel-1" aria-selected="false">Vision</a>
                                    </li>
                                </ul>
                                <div class="tab-content tabs-content py-3 mt-2">
                                    <div class="tab-pane active" id="pill-tabpanel-0" role="tabpanel" aria-labelledby="pill-tab-0">
                                        <div class="inner-box">
                                            <p>{{$homepage_setting ? $homepage_setting->mission_description : ""}}</p>
                                            <ul class="list-style-one clearfix">
                                                @foreach(json_decode($homepage_setting->mission_service) as $mission)
                                                    <li>{{ $mission }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="pill-tabpanel-1" role="tabpanel" aria-labelledby="pill-tab-1">
                                        <div class="inner-box">
                                            <p>{{$homepage_setting ? $homepage_setting->vision_description : ""}}</p>
                                            <ul class="list-style-one clearfix">
                                                @foreach(json_decode($homepage_setting->vision_service) as $vision)
                                                    <li>{{ $vision }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about-section end -->

    <!-- service-section -->
    <section class="service-section">
        <div class="container-fluid px-lg-4">
            <div class="sec-title mb_50 centred">
                <span class="sub-title mb_12 fs-6">Our services</span>
                <h2 class="fw-bold">Expert Ambulance Services</h2>
            </div>
            <div class="tabs-box">
                <div class="tab-btn-box">
                    <div class="tab-btns tab-buttons clearfix">
                        @foreach($services4 as $key => $service)
                            <div class="tab-btn text-uppercase {{$key == 0 ? 'active-btn':''}} " data-tab="#tab-{{$service->id}}">{{$service->name}}</div>
                        @endforeach
                    </div>
                </div>
                <div class="tabs-content">
                    <div class="shape" style="background-image: url({{asset('/')}}frontend/assets/images/shape/shape-1.png);"></div>
                    @foreach($services4 as $key => $service)
                    <div class="tab {{$key == 0 ? 'active-tab':''}}" id="tab-{{$service->id}}">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                                <div class="content-box">
                                    <h2 class="text-uppercase">{{$service->name}}</h2>
                                    <p>{{$service->service_equipment_description}}</p>
                                    <ul class="list-style-one clearfix">
                                        @foreach($service->service_equipment as $sequipment)
                                            <li>{{$sequipment->title}}</li>
                                        @endforeach
                                    </ul>
                                    <a href="{{route('contact.us')}}" class="theme-btn btn-one">Get a Quote</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                                <div class="image-box pl_110 pb_50">
                                    <figure class="image image-1 image-hov-one"><img src="{{asset($service->image1)}}" alt=""></figure>
                                    <figure class="image image-2 image-hov-two"><img src="{{asset($service->image2)}}" alt=""></figure>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- service-section end -->

    <!-- funfact-section -->
    <section class="funfact-section pb-2 text-center">
        <div class="container-fluid px-lg-4">
            <div class="inner-container">
                <div class="row">
                    <!-- Single Block -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mb-lg-4">
                        <div class="funfact-block-one">
                            <div class="inner-box">
                                <div class="count-outer">
                                    <span class="odometer display-4 fw-bold" data-count="{{$homepage_setting->patient_served}}">{{$homepage_setting->patient_served}}</span>
                                    <span class="symble fs-3 fw-bold">+</span>
                                </div>
                                <p class="fs-6 fs-md-5">Patients Served</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mb-lg-4">
                        <div class="funfact-block-one">
                            <div class="inner-box">
                                <div class="count-outer">
                                    <span class="odometer display-4 fw-bold" data-count="{{$homepage_setting->operation_bases}}">{{$homepage_setting->operation_bases}}</span>
                                    <span class="symble fs-3 fw-bold">+</span>
                                </div>
                                <p class="fs-6 fs-md-5">Own Ambulance</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mb-lg-4">
                        <div class="funfact-block-one">
                            <div class="inner-box">
                                <div class="count-outer">
                                    <span class="odometer display-4 fw-bold" data-count="{{$homepage_setting->specialised_vehicles}}">{{$homepage_setting->specialised_vehicles}}</span>
                                    <span class="symble fs-3 fw-bold">+</span>
                                </div>
                                <p class="fs-6 fs-md-5">District Covered</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mb-lg-4">
                        <div class="funfact-block-one">
                            <div class="inner-box">
                                <div class="count-outer">
                                    <span class="odometer display-4 fw-bold" data-count="{{$homepage_setting->frontline_staff}}">{{$homepage_setting->frontline_staff}}</span>
                                    <span class="symble fs-3 fw-bold">+</span>
                                </div>
                                <p class="fs-6 fs-md-5">Ambulance Partner</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- funfact-section end -->

    <!--PartnerShip Solution Section-->
    <section class="partnership-Solution container-fluid py-5">
        <h1 class="text-center my-5 fw-bold display-5 display-md-4 display-lg-3">
            Partnership Solution
        </h1>
        <div class="row g-4 justify-content-center">
            @foreach($solutions3 as $sol)
            <div class="col-lg-4 col-md-6 col-12">
                <a href="{{route('solution',$sol->slug)}}">
                    <div class="card border rounded-4 bg-theme h-100">
                        <div class="card-body p-0 border-0">
                            <img src="{{asset($sol->thumbnail)}}" class="img-fluid w-100 rounded-top-4" alt="Hospital Solution">
                        </div>
                        <div class="card-footer text-center rounded-bottom-4 border-0">
                            <h3 class="mb-0 text-white p-3 fw-light fs-4 fs-md-5 fs-lg-4">
                                {{ucwords($sol->name)}}
                            </h3>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    <!--PartnerShip Solution Section end-->

    <!--Offer section -->
    <section class="offer-section container-fluid pt-5">
        <h1 class="text-center fw-light my-5 text-theme" style="font-size: 30px;">OFFERS</h1>
        <div class="row">
            <div class="owl-carousel owl-theme">
                @foreach($offers as $offer)
                    <div class="item">
                        <a href="{{route('offer.details',$offer->slug)}}">
                            <div class="card border" style="background-color:#f5f5f5; border-radius: 10px; height: auto">
                                <div class="card-body p-0 border-0">
                                    <img src="{{ asset($offer->thumbnail) }}"
                                         class="img-fluid w-100" style="border-radius: 10px 10px 0 0"
                                         alt="Card Image">
                                </div>
                                <div class="card-footer border-0 p-4">
                                    <h6 class="mb-0 text-theme">{{$offer->name}}</h6>
                                    <p class="mb-0 text-dark fw-bold">{{$offer->short_description}}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--Offer section end-->


    <!--Partner section Start-->
    <section class="partner-section container-fluid pt-5">
        <h1 class="text-center fw-light my-5 text-theme" style="font-size: 30px;">Our Honored Partner</h1>
        <p class="text-theme text-center">
            Expanding our reach through partnerships with hospitals, corporates, and ambulance providers.
        </p>

        <div class="row mt-5">
            <div class="col-12">
                <div class="tabs-box">
                    <!-- Nav Pills -->
                    <ul class="nav nav-pills justify-content-center mb-4 flex-wrap" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active mx-2 my-1 py-1 px-4 fs-6 rounded-3"
                               style="border: 2px solid #0d5c37"
                               id="hospital-tab"
                               data-bs-toggle="pill"
                               href="#hospital-pane"
                               role="tab"
                               aria-controls="hospital-pane"
                               aria-selected="true">Hospital</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2 my-1 py-1 px-4 fs-6 rounded-3"
                               style="border: 2px solid #0d5c37"
                               id="corporate-tab"
                               data-bs-toggle="pill"
                               href="#corporate-pane"
                               role="tab"
                               aria-controls="corporate-pane"
                               aria-selected="false">Corporate</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2 my-1 py-1 px-4 fs-6 rounded-3"
                               style="border: 2px solid #0d5c37"
                               id="ambulance-tab"
                               data-bs-toggle="pill"
                               href="#ambulance-pane"
                               role="tab"
                               aria-controls="ambulance-pane"
                               aria-selected="false">Ambulance</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content py-3">
                        <!-- Hospital -->
                        <div class="tab-pane fade show active" id="hospital-pane" role="tabpanel" aria-labelledby="hospital-tab">
                            <div class="owl-carousel owl-theme">
                                @foreach($partners->where('type','hospital') as $partner)
                                <div class="item border rounded-3 p-2">
                                    <img src="{{asset($partner->image)}}" class="img-fluid rounded-3" alt="">
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Corporate -->
                        <div class="tab-pane fade" id="corporate-pane" role="tabpanel" aria-labelledby="corporate-tab">
                            <div class="owl-carousel owl-theme">
                                @foreach($partners->where('type','corporate') as $partner)
                                    <div class="item border rounded-3 p-2">
                                        <img src="{{asset($partner->image)}}" class="img-fluid rounded-3" alt="">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Ambulance -->
                        <div class="tab-pane fade" id="ambulance-pane" role="tabpanel" aria-labelledby="ambulance-tab">
                            <div class="owl-carousel owl-theme">
                                @foreach($partners->where('type','ambulance') as $partner)
                                    <div class="item border rounded-3 p-2">
                                        <img src="{{asset($partner->image)}}" class="img-fluid rounded-3" alt="">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Partner section end-->

    {{--News & Blogs--}}
    <section class="container-fluid px-3 px-md-4 my-4">
        <h1 class="text-center fw-bold my-5 text-dark" style="font-size: 30px;">
            <a class="text-dark text-decoration-none" href="{{route('blogs','all')}}">NEWS & BLOG</a>
        </h1>
        <div class="row">
            <!-- Left Big Card -->
            @foreach($singleNews as $single)
                <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                <div class="card h-100 border rounded-3 bg-light shadow-sm">
                    <div class="card-body p-3 p-md-4 p-lg-5">
                        <a href="{{route('blog.details',$single->slug)}}">
                            <img style="object-fit: cover" src="{{asset($single->image)}}" class="img-fluid w-100 rounded-3 mb-3" alt="{{$single->title}}"></a>
                        <div>
                            <h5 class="pb-2"><a class="text-theme" href="{{route('blog.details',$single->slug)}}">{{$single->title}}</a></h5>
                            <p class="mb-0 text-dark fw-bold fs-6 fs-lg-5">{{ \Illuminate\Support\Str::limit($single->short_description, 200, '...') }}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-4 flex-wrap gap-3">
                            <a href="{{route('blog.details',$single->slug)}}" class="btn btn-outline-success px-4 py-2">Show More</a>
                            @php
                                $shareUrl = urlencode(route('blog.details', $single->slug));
                                $shareTitle = urlencode($single->slug);
                            @endphp

                            <div class="d-flex gap-2">
                                <a class="g-2 fs-5" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank">
                                    <i class="fa fa-facebook fs-6 text-theme"></i>
                                </a>
                                <a class="g-2 fs-5" href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank">
                                    <i class="fa fa-twitter fs-6 text-theme"></i>
                                </a>
                                <a class="g-2 fs-5" href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareTitle }}" target="_blank">
                                    <i class="fa fa-linkedin fs-6 text-theme"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Right Small Cards -->

                <div class="col-12 col-lg-6">
                    <div class="row">
                        @foreach($recentBlogs as $news)
                            <div class="col-12 col-sm-6 mb-4">
                                <div class="card h-100 border rounded-3 bg-light">
                                    <div class="card-body p-3">
                                        <a href="{{route('blog.details',$news->slug)}}">
                                            <img src="{{asset($news->image)}}" style="object-fit: cover" class="img-fluid rounded-3 w-100" alt="{{$news->title}}">
                                        </a>
                                        <div class="pt-3">
                                            <h6 class="pb-2">
                                                <a class="text-theme" href="{{route('blog.details',$news->slug)}}">{{$news->title}}</a>
                                            </h6>
                                            <p class="mb-0 text-dark fw-bold">{{ \Illuminate\Support\Str::limit($news->short_description, 200, '...') }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center pt-3 flex-wrap gap-2">
                                            <a href="{{route('blog.details',$news->slug)}}" class="btn btn-outline-success btn-sm px-3">Show More</a>
                                            @php
                                                $shareUrl = urlencode(route('blog.details', $news->slug));
                                                $shareTitle = urlencode($news->slug);
                                            @endphp

                                            <div class="d-flex gap-2">
                                                <a class="g-2 fs-5" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank">
                                                    <i class="fa fa-facebook fs-6 text-theme"></i>
                                                </a>
                                                <a class="g-2 fs-5" href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank">
                                                    <i class="fa fa-twitter fs-6 text-theme"></i>
                                                </a>
                                                <a class="g-2 fs-5" href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareTitle }}" target="_blank">
                                                    <i class="fa fa-linkedin fs-6 text-theme"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

        </div>
    </section>
    {{--News & Blogs End--}}

    {{--Faq Section--}}
    <section class="faq-section py-5" id="faqsec">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <h2 class="mb-4 fw-bold">Frequently asked questions</h2>
                    <div class="accordion" id="faqAccordion">
                        <!-- Item 1 -->
                        @foreach($faqs as $faq)
                            <div class="accordion-item faq-box mb-3">
                            <h2 class="accordion-header" id="faqHeadingOne{{$faq->id}}">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne{{$faq->id}}" aria-expanded="false" aria-controls="faqCollapseOne{{$faq->id}}">
                                    {{$faq->question}}
                                </button>
                            </h2>
                            <div id="faqCollapseOne{{$faq->id}}" class="accordion-collapse collapse" aria-labelledby="faqHeadingOne{{$faq->id}}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--Faq Section End--}}

    {{--Testimonial Section--}}
    <section class="py-5" style="background-color: #e2eae7">
        <div class="container text-center mb-4">
            <h2 class="fw-bold my-4">TESTIMONIALS</h2>

            <ul class="nav nav-pills justify-content-center row g-2 review-tabs" id="reviewTabs" role="tablist">
                <li class="nav-item col-6 col-lg-3" role="presentation">
                    <button class="nav-link active w-100 py-2" id="customer-tab" data-bs-toggle="pill" data-bs-target="#customer" type="button" role="tab">
                        CUSTOMER REVIEW
                    </button>
                </li>
                <li class="nav-item col-6 col-lg-3" role="presentation">
                    <button class="nav-link w-100 py-2" id="partner-tab" data-bs-toggle="pill" data-bs-target="#partner" type="button" role="tab">
                        PARTNER REVIEW
                    </button>
                </li>
                <li class="nav-item col-6 col-lg-3" role="presentation">
                    <button class="nav-link w-100 py-2" id="hospital-tab" data-bs-toggle="pill" data-bs-target="#hospital" type="button" role="tab">
                        HOSPITAL REVIEW
                    </button>
                </li>
                <li class="nav-item col-6 col-lg-3" role="presentation">
                    <button class="nav-link w-100 py-2" id="corporate-tab" data-bs-toggle="pill" data-bs-target="#corporate" type="button" role="tab">
                        CORPORATE REVIEW
                    </button>
                </li>
            </ul>
        </div>

        <div class="container">
            <div class="tab-content" id="reviewTabsContent">
                <!-- Example Customer Tab -->
                <div class="tab-pane fade show active" id="customer" role="tabpanel">
                    <div id="customerCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($testimonials->where('review_type','customer') as $index => $testimonial)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <div class="row align-items-center g-4 text-start">
                                        <!-- Image Column -->
                                        <div class="col-lg-5 d-flex justify-content-center position-relative">
                                            <div class="position-relative">
                                                <img src="{{ asset($testimonial->image) }}" alt="Customer"
                                                     class="img-fluid rounded shadow border border-3 border-white"
                                                     style="max-height: 300px; object-fit: cover; position: relative; z-index: 2;">
                                                <div class="position-absolute top-50 translate-middle-y"
                                                     style="right: -25px; width: 40px; height: 85%; background-color: #0d5c37; border-radius: 6px; z-index: 1;">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Text Column -->
                                        <div class="col-lg-7">
                                            <p class="text-dark fs-5 fw-medium mb-4">
                                                {{ $testimonial->review }}
                                            </p>
                                            <div class="row">
                                                <div class="col-7">
                                                    <p class="mb-0 text-theme fw-bold">{{ $testimonial->name }}</p>
                                                    <small class="text-theme d-block">{{ $testimonial->designation }}</small>
                                                    <small class="text-theme">{{ $testimonial->company_name }}</small>
                                                </div>
                                                <div class="col-5 d-flex align-items-end justify-content-end">
                                                    {{-- Dynamic star rating --}}
                                                    <span class="fs-5 text-warning">
                                                        @for ($i = 0; $i < $testimonial->rating; $i++)
                                                            ★
                                                        @endfor
                                                        @for ($i = $testimonial->rating; $i < 5; $i++)
                                                            ☆
                                                        @endfor
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            @foreach($testimonials->where('review_type','customer')->values() as $index => $testimonial)
                                <button type="button" data-bs-target="#customerCarousel" data-bs-slide-to="{{$index}}" class="{{ $loop->first ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Same pattern for partner, hospital, corporate -->
                <div class="tab-pane fade" id="partner" role="tabpanel">
                    <div id="partnerCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($testimonials->where('review_type','partner')->values() as $index => $testimonial)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <div class="row align-items-center g-4 text-start">
                                        <!-- Image Column -->
                                        <div class="col-lg-5 d-flex justify-content-center position-relative">
                                            <div class="position-relative">
                                                <img src="{{ asset($testimonial->image) }}" alt="Customer"
                                                     class="img-fluid rounded shadow border border-3 border-white"
                                                     style="max-height: 300px; object-fit: cover; position: relative; z-index: 2;">
                                                <div class="position-absolute top-50 translate-middle-y"
                                                     style="right: -25px; width: 40px; height: 85%; background-color: #0d5c37; border-radius: 6px; z-index: 1;">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Text Column -->
                                        <div class="col-lg-7">
                                            <p class="text-dark fs-5 fw-medium mb-4">
                                                {{ $testimonial->review }}
                                            </p>
                                            <div class="row">
                                                <div class="col-7">
                                                    <p class="mb-0 text-theme fw-bold">{{ $testimonial->name }}</p>
                                                    <small class="text-theme d-block">{{ $testimonial->designation }}</small>
                                                    <small class="text-theme">{{ $testimonial->company_name }}</small>
                                                </div>
                                                <div class="col-5 d-flex align-items-end justify-content-end">
                                                    {{-- Dynamic star rating --}}
                                                    <span class="fs-5 text-warning">
                                                        @for ($i = 0; $i < $testimonial->rating; $i++)
                                                            ★
                                                        @endfor
                                                        @for ($i = $testimonial->rating; $i < 5; $i++)
                                                            ☆
                                                        @endfor
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            @foreach($testimonials->where('review_type','partner')->values() as $index => $testimonial)
                                <button type="button" data-bs-target="#partnerCarousel" data-bs-slide-to="{{$index}}" class="{{ $loop->first ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Copy customerCarousel structure and update IDs & images -->
                <div class="tab-pane fade" id="hospital" role="tabpanel">
                    <div id="hospitalCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($testimonials->where('review_type','hospital')->values() as $index => $testimonial)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <div class="row align-items-center g-4 text-start">
                                        <!-- Image Column -->
                                        <div class="col-lg-5 d-flex justify-content-center position-relative">
                                            <div class="position-relative">
                                                <img src="{{ asset($testimonial->image) }}" alt="Customer"
                                                     class="img-fluid rounded shadow border border-3 border-white"
                                                     style="max-height: 300px; object-fit: cover; position: relative; z-index: 2;">
                                                <div class="position-absolute top-50 translate-middle-y"
                                                     style="right: -25px; width: 40px; height: 85%; background-color: #0d5c37; border-radius: 6px; z-index: 1;">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Text Column -->
                                        <div class="col-lg-7">
                                            <p class="text-dark fs-5 fw-medium mb-4">
                                                {{ $testimonial->review }}
                                            </p>
                                            <div class="row">
                                                <div class="col-7">
                                                    <p class="mb-0 text-theme fw-bold">{{ $testimonial->name }}</p>
                                                    <small class="text-theme d-block">{{ $testimonial->designation }}</small>
                                                    <small class="text-theme">{{ $testimonial->company_name }}</small>
                                                </div>
                                                <div class="col-5 d-flex align-items-end justify-content-end">
                                                    {{-- Dynamic star rating --}}
                                                    <span class="fs-5 text-warning">
                                                        @for ($i = 0; $i < $testimonial->rating; $i++)
                                                            ★
                                                        @endfor
                                                        @for ($i = $testimonial->rating; $i < 5; $i++)
                                                            ☆
                                                        @endfor
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            @foreach($testimonials->where('review_type','hospital')->values() as $index => $testimonial)
                                <button type="button" data-bs-target="#hospitalCarousel" data-bs-slide-to="{{$index}}" class="{{ $loop->first ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="corporate" role="tabpanel">
                    <div id="corporateCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($testimonials->where('review_type','corporate')->values() as $index => $testimonial)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <div class="row align-items-center g-4 text-start">
                                        <!-- Image Column -->
                                        <div class="col-lg-5 d-flex justify-content-center position-relative">
                                            <div class="position-relative">
                                                <img src="{{ asset($testimonial->image) }}" alt="Customer"
                                                     class="img-fluid rounded shadow border border-3 border-white"
                                                     style="max-height: 300px; object-fit: cover; position: relative; z-index: 2;">
                                                <div class="position-absolute top-50 translate-middle-y"
                                                     style="right: -25px; width: 40px; height: 85%; background-color: #0d5c37; border-radius: 6px; z-index: 1;">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Text Column -->
                                        <div class="col-lg-7">
                                            <p class="text-dark fs-5 fw-medium mb-4">
                                                {{ $testimonial->review }}
                                            </p>
                                            <div class="row">
                                                <div class="col-7">
                                                    <p class="mb-0 text-theme fw-bold">{{ $testimonial->name }}</p>
                                                    <small class="text-theme d-block">{{ $testimonial->designation }}</small>
                                                    <small class="text-theme">{{ $testimonial->company_name }}</small>
                                                </div>
                                                <div class="col-5 d-flex align-items-end justify-content-end">
                                                    {{-- Dynamic star rating --}}
                                                    <span class="fs-5 text-warning">
                                                        @for ($i = 0; $i < $testimonial->rating; $i++)
                                                            ★
                                                        @endfor
                                                        @for ($i = $testimonial->rating; $i < 5; $i++)
                                                            ☆
                                                        @endfor
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            @foreach($testimonials->where('review_type','corporate')->values() as $index => $testimonial)
                                <button type="button" data-bs-target="#corporateCarousel" data-bs-slide-to="{{$index}}" class="{{ $loop->first ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--Testimonial Section ENd--}}

    {{--Contact Section--}}
    @include('frontend.component.contact_section')
    {{--Contact Section End--}}

@endsection
@push('js')
    <!-- Owl Carousel JS (init script) -->
    <script>
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: false,
            autoplay: true,
            autoplayTimeout: 1000,
            responsive: {
                0: { items: 1 },
                576: { items: 2 },
                768: { items: 3 },
                992: { items: 5 }
            }
        });
    </script>
@endpush
