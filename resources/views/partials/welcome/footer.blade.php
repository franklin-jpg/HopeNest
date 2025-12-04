
        <footer class="main-footer">
            <!--Widgets Section-->


            <div class="widgets-section">

                
                <div class="container">
                    <div class="row">
                        <!--Big Column-->

    

                        <article class="big-column col-md-6 col-sm-12 col-xs-12">
                            <div class="row clearfix">
                                <!--Footer Column-->


                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="footer-widget about-column">
                                        <div class="section-title">
                                            <h4>About Us</h4>
                                        </div>


                                        <div class="text">
                                            <p>We partner with over 320 amazing projects worldwide, and have given over $150 million in cash and product grants to other groups since 2011. We also operate our own dynamic suite of Signature Programs, million in cash and product grants to others</p>
                                        </div>


                                        <div class="link">
                                            <a class="default_link" href="{{ route('about-us') }}">Read More <i class="fa fa-long-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <!--Footer Column-->


                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="footer-widget link-column">
                                        <div class="section-title">
                                            <h4>Usefull Links</h4>
                                        </div>


                                        <div class="widget-content">
                                            <ul class="list">
                                                <li>
                                                    <a href="{{ route('about-us') }}">About Our Hopenest</a>
                                                </li>


                                                <li>
                                                    <a href="{{ route('all.campaigns') }}">Recent Campaigns</a>
                                                </li>


                                                <li>
                                                    <a href="volunteer.html">Become a Volunteer</a>
                                                </li>


                                                <li>
                                                    <a href="#">Our Donators</a>
                                                </li>


                                                <li>
                                                    <a href="#">Sponsers</a>
                                                </li>


                                                <li>
                                                    <a href="{{ route('impact-stories.index') }}">Impact Stories</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <!--Big Column-->

                        <article class="big-column col-md-6 col-sm-12 col-xs-12">
                            <div class="row clearfix">
                                <!--Footer Column-->


                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="footer-widget post-column">
                                        <div class="section-title">
                                            <h4>Recent Post</h4>
                                        </div>


                                        <div class="post-list">
                                            <div class="post">
                                                <a href="#">
                                                <h5>Car show event photos 2015</h5></a>

                                                <div class="post-info">
                                                    March 14, 2017
                                                </div>
                                            </div>


                                            <div class="post">
                                                <a href="#">
                                                <h5>Hope Kids Holiday Party</h5></a>

                                                <div class="post-info">
                                                    February 21, 2017
                                                </div>
                                            </div>


                                            <div class="post">
                                                <a href="#">
                                                <h5>hopenest Bikini Car wash photos</h5></a>

                                                <div class="post-info">
                                                    January 15, 2017
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Footer Column-->


                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="footer-widget contact-column">
                                        <div class="section-title">
                                            <h4>Get In Touch</h4>
                                        </div>


                                        <ul class="contact-info">
                                            <li><i class="icon-arrows"></i><span>Address:</span> Park Drive, Varick Str<br>
                                            New York, NY 10012, USA</li>


                                            <li><i class="icon-phone"></i> <span>Phone:</span> 09067876667 &<br>
                                            07064941365</li>


                                            <li><i class="icon-back"></i><span>Email:</span> Emailus@Hopenest.com</li>
                                            
                                        </ul>

                                        
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>

            </div>

            </div>
        </div>

        <!-- NEW: Newsletter Subscribe Section -->
        <div class="newsletter-section bg-orange-500 py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8 col-sm-12">
                        <h5 class="text-white mb-0 font-weight-bold">
                            <i class="fa fa-envelope mr-2"></i>
                            Stay Updated with HopeNest
                        </h5>
                        <small class="text-white-50 d-block text-gray-300">
                            Get notified about new campaigns & success stories
                        </small>
                    </div>
                    <div class="col-md-4 col-sm-12 mt-3 mt-md-0">
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex">
                            @csrf
                            <input 
                                type="email" 
                                name="email" 
                                placeholder="Enter your email" 
                                required
                                class="form-control mr-2 rounded-pill px-4 py-2"
                            >
                            <button 
                                type="submit"
                                class="btn btn-white rounded-pill px-5 font-weight-bold text-gray-300"
                            >
                                <i class="fa fa-paper-plane text-gray-300"></i> Subscribe
                            </button>
                        </form>
                        
                        <!-- Success/Error Messages -->
                        @if(session('newsletter_success'))
                            <div class="alert alert-success mt-2 small rounded-pill p-2">
                                <i class="fa fa-check-circle mr-2"></i>
                                {{ session('newsletter_success') }}
                            </div>
                        @endif
                        @if(session('newsletter_error'))
                            <div class="alert alert-danger mt-2 small rounded-pill p-2">
                                <i class="fa fa-exclamation-circle mr-2"></i>
                                {{ session('newsletter_error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        </footer>
        <!--Footer Bottom-->

        <section class="footer-bottom">
            <div class="container">
                <div class="pull-left copy-text">
                    <p><a href="#">Copyrights © 2017</a> All Rights Reserved. Powered by <a href="#">Hopenest</a></p>
                </div>
                <!-- /.pull-right -->


                <div class="pull-right get-text">
                    <a href="{{ route('volunteer.apply') }}">Join Us Now!</a>
                </div>
                <!-- /.pull-left -->
            </div>
            <!-- /.container -->
        </section>