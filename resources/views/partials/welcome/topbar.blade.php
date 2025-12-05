  <div class="top-bar">
            <div class="container">
                <div class="clearfix">
                    <div class="top-bar-text float_left">
                        <a href="{{ route('all.campaigns')}}">

                        <button class="thm-btn donate-box-btn m-6">donate</button>

                        </a>
                        
                        <p>No One Has Ever Become Poor By Giving!</p>
                    </div>


                    <div class="right-column float_right">
                        <ul class="list_inline contact-info">
                            <li><span class="icon-phone"></span>Phone: +32 456 789012</li>


                            <li><span class="icon-back"></span>Email: Mailus@Hopenest.com</li>
                        </ul>


                        {{-- <div class="" id="polyglotLanguageSwitcher">
                            <form action="#">
                                <select id="polyglot-language-options">
                                    <option id="en" selected value="en">
                                        English
                                    </option>

                                    <option id="fr" value="fr">
                                        French
                                    </option>

                                    <option id="de" value="de">
                                        German
                                    </option>

                                    <option id="it" value="it">
                                        Italian
                                    </option>

                                    <option id="es" value="es">
                                        Spanish
                                    </option>
                                </select>
                            </form>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
         <section class="theme_menu stricky">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="main-logo">
                            <h1 class="logo">HOPENEST <span class="text-orange-500">🕊️</span></h1>
                            <p id="logo-p"  class="text-[#fff] hover:text-orange-500 ">Embrace hope! <span>Find joy!</span></p> 
                        </div>
                    </div>


                    <div class="col-md-9 menu-column">
                        <nav class="defaultmainmenu" id="main_menu">
                            <ul class="defaultmainmenu-menu">
                                {{-- <li>
                                    <a href="{{ route('register') }}">Register</a>
                                </li> --}}
                             


                                <li class="active">
                                    <a href="#">Pages</a>

                                    <ul class="dropdown">
                                        <li>
                                            <a href="{{ route('about-us') }}">About HopeNest</a>
                                        </li>


                                        <li>
                                            <a href="team.html">Meet Our Team</a>
                                        </li>


                                        <li>
                                            <a href="{{ route('volunteer.apply') }}">Join as Volunteer</a>
                                        </li>


                                        <li>
                                            <a href="{{ route('faq') }}">FAQ's</a>
                                        </li>


                                        <li>
                                            <a href="testimonial.html">Testimonials</a>
                                        </li>


                                        <li>
                                            <a href="{{ route('contact') }}">Contact Us</a>
                                        </li>
                                    </ul>
                                </li>


                                <li>
                                    <a href="cause-1.html">Campaigns</a>

                                    <ul class="dropdown">
                                        <li>
                                            <a href="{{ route('all.campaigns') }}">Campaign Grid View</a>
                                        </li>


                                      
                                    </ul>
                                </li>


                          





                                

                                 <li>
                                    <a href="cause-1.html">Blogs</a>

                                    <ul class="dropdown">
                                        <li>
                                            <a href="{{ route('blog.index') }}">Blog Grid View</a>
                                        </li>


                                      
                                    </ul>
                                </li>


                                 <li>
                                    <a href="cause-1.html">Imapact Stories</a>

                                    <ul class="dropdown">
                                        <li>
                                            <a href="{{ route('impact-stories.index') }}">Impact Stories Grid View</a>
                                        </li>


                                      
                                    </ul>
                                </li>
                                  <li>
                                      @auth

                        @if (auth()->user()->role === 'admin')
                           <a href="{{ route('admin.dashboard') }}" class="et-btn  flex items-center justify-center gap-x-[15px] h-[50px] px-[15px] text-etBlue font-medium text-[17px] rounded-full group">
                       
                        Dashboard
                    </a>
                        @elseif (auth()->user()->role === 'user')
                               <a href="{{ route('user.dashboard') }}" class="et-btn  flex items-center justify-center gap-x-[15px] h-[50px] px-[15px] text-etBlue font-medium text-[17px] rounded-full group">
                       
                        Dashboard
                    </a>
                    </a>
                        @elseif (auth()->user()->role === 'volunteer')
                               <a href="{{ route('volunteer.dashboard') }}" class="et-btn  flex items-center justify-center gap-x-[15px] h-[50px] px-[15px] text-etBlue font-medium text-[17px] rounded-full group">
                       
                        Dashboard
                    </a>
                        @endif
                    @endauth

                    @guest
                          <a href="{{ route('login') }}" class="et-btn  flex items-center justify-center gap-x-[15px] h-[50px] px-[15px] text-etBlue font-medium text-[17px] rounded-full group">
                       
                        Login
                    </a>
                    @endguest
                                </li>
                            </ul>
                        </nav>
                    </div>


                    <div class="right-column">
                        <div class="nav_side_content">
                            <ul class="social-icon">
                                <li>
                                    <a href="https://www.facebook.com/share/1FtP1TUXTH/"><i class="fa-brands fa-facebook-f"></i></a>
                                </li>


                                <li>
                                    <a href="https://x.com/Chinaza4Okereke?t=5eqUENrIIffDmAwF_VMyMQ&s=09"><i class="fa-brands fa-x-twitter"></i></a>
                                </li>


                                <li>
                                    <a href="#"><i class="fa-brands fa-google-plus-g"></i></a>
                                </li>
                            </ul>


                            <div class="search_option">
                                <button aria-expanded="false" aria-haspopup="true" class="search tran3s dropdown-toggle color1_bg" data-toggle="dropdown" id="searchDropdown"><i aria-hidden="true" class="fa fa-search"></i></button>

                                <form action="#" aria-labelledby="searchDropdown" class="dropdown-menu">
                                    <input placeholder="Search..." type="text"> <button><i aria-hidden="true" class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>