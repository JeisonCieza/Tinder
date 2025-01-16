<div class="m-auto md:p-10 w-full h-full relative">
    <div class="relative h-full md:h-[600px] w-full md:w-96 m-auto">
        <!-- x-data -->
        @foreach ($users as $i => $user)

        <div
            @swipedright.window="console.log('right')"
            @swipedleft.window="console.log('left')"
            @swipedup.window="console.log('left')"
            wire:key="swipe-{{$user->id}}"
            x-data="{
            profile:false,
            isSwiping:false,
            swipingLeft:false,
            swipingRight:false,
            swipingUp:false,
            swipeRight: function(){

                moveOutWidth= document.body.clientWidth *1.5;

                $el.style.transform= 'translate(' +moveOutWidth+ 'px, -100px ) rotate(-30deg)';

                setTimeout(()=>{
                    $el.remove();
                },300);

                <!-- dispatch -->
                $dispatch('swipedright',{user:'{{$user->id}}'});
            },
            swipeLeft: function(){

                moveOutWidth= document.body.clientWidth *1.5;

                $el.style.transform= 'translate(' + -moveOutWidth+ 'px, -100px ) rotate(-30deg)';

                setTimeout(()=>{
                    $el.remove();
                },300);

                <!-- dispatch -->
                $dispatch('swipedleft',{user:'{{$user->id}}'});
            },
            swipeUp: function(){

                moveOutWidth= document.body.clientWidth *1.5;

                $el.style.transform= 'translate(0px, '+ -moveOutWidth+ 'px) rotate(-20deg)';

                setTimeout(()=>{
                    $el.remove();
                },300);

                <!-- dispatch -->
                $dispatch('swipedup',{user:'{{$user->id}}'} );
            },
            }"

            x-init="

            element= $el;

            <!-- Initialize hamer.js -->
             var hammertime = new Hammer(element);

            <!-- let pan support all directions -->
            hammertime.get('pan').set({
                direction: Hammer.DIRECTION_ALL,
                touchAction: 'pan'
            });

            <!-- ON pan -->
            hammertime.on('pan', function(event){

                isSwiping=true;

                if(event.deltaX===0) return;
                if(event.center.x===0 && event.center.y===0) return;

                <!-- Swiped right -->
                if(event.deltaX > 20){
                    swipingRight=true;
                    swipingLeft=false;
                    swipingUp=false;
                }

                <!-- Swipedleft -->
                else if(event.deltaX < -20){
                    swipingRight=false;
                    swipingLeft=true;
                    swipingUp=false;
                }

                <!-- Suuper like Swiped Up -->
                else if(event.deltaY < -50 && Math.abs(event.deltaX) < 20) {
                    swipingRight=false;
                    swipingLeft=false;
                    swipingUp=true;
                }

                <!-- Rotate -->
                var rotate= event.deltaX/10;

                <!-- Apply transformation to rotate only in X direction in somewhat Clockwise and Anit clokwise -->

                event.target.style.transform= 'translate('+ event.deltaX + 'px,' + event.deltaY + 'px) rotate(' +rotate+ 'deg)';
            });

            hammertime.on('panend',function(event) {
                <!-- reset states -->
                isSwiping=false;
                swipingLeft=false;
                swipingRight=false;
                swipingUp=false;

                <!-- set threshold -->
                let horizontalThreshold=200;
                let verticalThreshold =200;

                <!-- velocity threshold -->
                let velocityXThreshold= 0.5;
                let velocityYThreshold= 0.5;

                <!-- Determine keep -->
                let keep= Math.abs(event.deltaX) < horizontalThreshold && Math.abs(event.velocityX)<velocityXThreshold &&
                          Math.abs(event.deltaY) < verticalThreshold && Math.abs(event.velocityY)< velocityYThreshold;

                console.log('keep'+ keep);
                console.log('event.deltaX' + event.deltaX);

                if(keep){

                    <!-- adjust the duration and timing as need -->
                    event.target.style.transition='transform 0.3s ease-in-out';
                    event.target.style.transform='';
                    $el.style.transform='';

                    <!-- clear the transition -->
                    setTimeout(()=> {
                        event.target.style.transition='';
                        event.target.style.transform='';
                        $el.style.transform='';
                    },300);

                }
                else {

                    var moveOutWidth = document.body.clientWidth;
                    var moveOutHeight = document.body.clientHeight;

                    <!-- Decie to push let right or up -->

                    <!-- Swipe right -->

                    if(event.deltaX > 20) {
                        event.target.style.transform = 'translate(' + moveOutWidth +'px, 10px)';
                        $dispatch('swipedright',{user:'{{$user->id}}'});
                    }

                    <!-- swipeLeft -->
                    else if(event.deltaX < -20) {
                        event.target.style.transform= 'translate(' + -moveOutWidth +'px, 10px)';
                        $dispatch('swipedleft',{user:'{{$user->id}}'});
                    }
                    else if(event.deltaY <- 50 && Math.abs(event.deltaX)<20){
                        event.target.style.transform= 'translate(0px,' + -moveOutHeight + 'px)';
                        $dispatch('swipedup',{user:'{{$user->id}}'});
                    }

                    event.target.remove();
                    $el.remove();
                }
            });
            "
            :class="{'transform-none cursor-grab':isSwiping}"
            class="absolute inset-0 m-auto transform ease-in-out duration-300 rounded-xl cursor-pointer z-50">

            <!-- swipe card -->
            <div
                x-show="!profile"
                x-transition.duration.150ms.origin.bottom
                class="relative overflow-hidden w-full h-full rounded-xl bg-cover bg-white">

                @php
                $slides=[
                'https://randomuser.me/api/portraits/women/' . rand(1, 20) . '.jpg',
                'https://randomuser.me/api/portraits/women/' . rand(1, 20) . '.jpg',
                'https://randomuser.me/api/portraits/women/' . rand(1, 20) . '.jpg',

                ]

                @endphp

                <!-- Carousel section -->
                <section x-data="{activeSlide:1, slides:@js($slides)}">
                    <!-- Slides -->
                    <template x-for="(image,index) in slides" :key="index">
                        <img x-show="activeSlide===index + 1" :src="image" alt="image" class="absolute inset-0 pointer-events-none w-full h-full object-cover">
                    </template>

                    <!-- pagination -->
                    <div
                        draggable="true"
                        :class="{'hidden':slides.length==1}"
                        class="absolute top-1 inset-x-0 z-10 w-full flex items-center justify-center">
                        <template x-for="(image,index) in slides" :key="index">
                            <button
                                @click="activeSlide=index+1"
                                :class="{'bg-white':activeSlide===index +1,'bg-gray-500':activeSlide !== index+1}"
                                class="flex-1 w-4 h-2 mx-1 rounded-full overflow-hidden">
                            </button>
                        </template>
                    </div>

                    <!-- Prev button -->
                    <button
                        draggable="true"
                        :class="{'hidden':slides.length==1}"
                        @click="activeSlide = activeSlide ===1? slides.length:activeSlide-1"
                        class="absolute left-2 top-1/2 my-auto">

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="3" stroke="currentColor" class="size-6 text-white">
                            <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
                        </svg>


                    </button>

                    <!-- Next button -->
                    <button
                        draggable="true"
                        :class="{'hidden':slides.length==1}"
                        @click="activeSlide = activeSlide === slides.length ? 1 : activeSlide + 1"
                        class="absolute right-2 top-1/2 my-auto">

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="3" stroke="currentColor" class="size-6 text-white">
                            <path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                        </svg>

                    </button>

                </section>

                <!-- Swiper indicators -->
                <div class="pointer-events-none ">
                    <span
                        x-cloak
                        :class="{'invisible':!swipingRight}"
                        class="border-2 rounded-md p-1 px-2 border-green-500 text-green-500 text-4xl capitalize font-extrabold top-10 left-5 -rotate-12 absolute z-5">
                        LIKE
                    </span>
                    <span
                        x-cloak
                        :class="{'invisible':!swipingLeft}"
                        class="border-2 rounded-md p-1 px-2 border-red-500 text-red-500 text-4xl capitalize font-extrabold top-10 right-5 rotate-12 absolute z-5">
                        NOPE
                    </span>
                    <span
                        x-cloak
                        :class="{'invisible':!swipingUp}"
                        class="border-2 rounded-md p-1 px-2 border-red-500 text-red-500 text-4xl capitalize font-extrabold bottom-48 max-w-fit inset-x-0 mx-auto -rotate-12 absolute z-5">
                        SUPER LIKE
                    </span>
                </div>

                <!-- information and actions -->
                <section class="absolute inset-x-0 bottom-0 inset-y-1/2 py-2 bg-gradient-to-t from-black to-black/0 pointer-events-none">
                    <div class="flex flex-col h-full gap-2.5 mt-auto p-5 text-white">
                        <!-- personal details -->
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-10">
                                <h4 class="font-bold text-3xl">
                                    {{$user->name}} {{$user->age}}
                                </h4>

                                <p class="text-lg line-clamp-3">
                                    {{$user->about}}
                                </p>
                            </div>

                            <!-- Open profile -->
                            <div class="col-span-2 justify-end flex pointer-events-auto">
                                <button @click="profile =!profile" draggable="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-white">
                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                    </svg>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-5 gap-1 items-center mt-auto">
                            <!-- rewind -->
                            <div>
                                <button draggable="true"
                                    class="rounded-full border-2 pointer-events-auto group border-yellow-600 p-2 shrink-0 max-w-fit flex items-center text-yellow-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6 w-10 h-10  shrink-0 m-auto group-hover:scale-105 transition-transform stroke-2 stroke-current">
                                        <path fill-rule="evenodd" d="M9.53 2.47a.75.75 0 0 1 0 1.06L4.81 8.25H15a6.75 6.75 0 0 1 0 13.5h-3a.75.75 0 0 1 0-1.5h3a5.25 5.25 0 1 0 0-10.5H4.81l4.72 4.72a.75.75 0 1 1-1.06 1.06l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                    </svg>

                                </button>
                            </div>
                            <!-- swipe left -->
                            <div>
                                <button
                                    draggable="true"
                                    @click="swipeLeft()"
                                    class="rounded-full border-2 pointer-events-auto group border-red-600 p-2 shrink-0 max-w-fit flex items-center text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        stroke-width="3" stroke="currentColor"
                                        class="size-6 w-10 h-10  shrink-0 m-auto group-hover:scale-105 transition-transform ">
                                        <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                    </svg>


                                </button>
                            </div>
                            <!-- Super Like -->
                            <div>
                                <button
                                    draggable="true"
                                    @click="swipeUp()"
                                    class="rounded-full border-2 pointer-events-auto group border-blue-600 p-2 shrink-0 max-w-fit flex items-center text-blue-600 scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6 w-10 h-10  shrink-0 m-auto group-hover:scale-105 transition-transform">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <!-- Swipe Right -->
                            <div>
                                <button
                                    draggable="true"
                                    @click="swipeRight()"
                                    class="rounded-full border-2 pointer-events-auto group border-green-600 p-2 shrink-0 max-w-fit flex items-center text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6 w-10 h-10  shrink-0 m-auto group-hover:scale-105 transition-transform">
                                        <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                                    </svg>

                                </button>
                            </div>
                            <!-- Boost -->
                            <div>
                                <button draggable="true"
                                    class="rounded-full border-2 pointer-events-auto group border-purple-600 p-2 shrink-0 max-w-fit flex items-center text-purple-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6 w-10 h-10  shrink-0 m-auto group-hover:scale-105 transition-transform">
                                        <path fill-rule="evenodd" d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z" clip-rule="evenodd" />
                                    </svg>


                                </button>
                            </div>
                        </div>
                    </div>

                </section>

            </div>

            <!-- profile card -->
            <div
                x-cloak
                x-show="profile"
                x-transition.duration.150ms.origin.top
                draggable="true"
                style="contain: content;"
                class="absolute inset-0 overflow-y-auto overflow-hidden overscroll-contain border rounded-xl bg-white space-y-4 custom-scrollbar">

                @php
                $slides=[
                'https://randomuser.me/api/portraits/women/' . rand(1, 20) . '.jpg',
                'https://randomuser.me/api/portraits/women/' . rand(1, 20) . '.jpg',
                'https://randomuser.me/api/portraits/women/' . rand(1, 20) . '.jpg',
                ]
                @endphp

                <!-- Carousel section -->
                <section class="relative h-96" x-data="{activeSlide:1, slides:@js($slides)}">
                    <!-- Slides -->
                    <template x-for="(image,index) in slides" :key="index">
                        <img x-show="activeSlide===index + 1" :src="image" alt="image" class="absolute inset-0 pointer-events-none w-full h-full object-cover">
                    </template>

                    <!-- pagination -->
                    <div
                        draggable="true"
                        :class="{'hidden':slides.length==1}"
                        class="absolute top-1 inset-x-0 z-10 w-full flex items-center justify-center">
                        <template x-for="(image,index) in slides" :key="index">
                            <button
                                @click="activeSlide=index+1"
                                :class="{'bg-white':activeSlide===index +1,'bg-gray-500':activeSlide !== index+1}"
                                class="flex-1 w-4 h-2 mx-1 rounded-full overflow-hidden">
                            </button>
                        </template>
                    </div>

                    <!-- Prev button -->
                    <button
                        draggable="true"
                        :class="{'hidden':slides.length==1}"
                        @click="activeSlide = activeSlide ===1? slides.length:activeSlide-1"
                        class="absolute left-2 top-1/2 my-auto">

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="3" stroke="currentColor" class="size-6 text-white">
                            <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
                        </svg>


                    </button>

                    <!-- Next button -->
                    <button
                        draggable="true" :class="{'hidden':slides.length==1}"
                        @click="activeSlide = activeSlide === slides.length ? 1 : activeSlide + 1"
                        class="absolute right-2 top-1/2 my-auto">

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="3" stroke="currentColor" class="size-6 text-white">
                            <path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- close profile button -->
                    <button @click="profile=false" class="absolute -bottom-4 right-3 bg-tinder p-3 hover:scale-110 transition-transform rounded-full max-w-fit max-h-fit text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="3" stroke="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v16.19l6.22-6.22a.75.75 0 1 1 1.06 1.06l-7.5 7.5a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 1 1 1.06-1.06l6.22 6.22V3a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                        </svg>
                    </button>

                </section>

                <!-- profile information -->
                <section class="grid gap-4 p-3">
                    <div class="flex items-center text-3xl gap-3 text-wrap">
                        <h3 class="font-bold"> {{$user->name}} </h3>
                        <span class="font-semibold text-gray-800">
                            {{$user->age}}
                        </span>
                    </div>

                    <!-- about -->
                    <ul>
                        <li class="items-center text-gray-600 text-lg">
                            {{$user->profession}}
                        </li>
                        <li class="items-center text-gray-600 text-lg">
                            {{$user->height?$user->height.' cm':''}}
                        </li>
                        <li class="items-center text-gray-600 text-lg">
                            {{$user->city?'Lives in '.$user->city:''}}
                        </li>
                    </ul>
                    <hr class="-mx-2.5">

                    <p class="text-gray-600">
                        {{$user->about}}
                    </p>

                    <!-- Relatioship goals -->
                    <div class="rounded-xl bg-green-200 h-24 px-4 py-2 max-w-fit flex gap-4 items-center">

                        <div class="text-3xl">👋</div>
                        <div class="grid w-4/5">
                            <span class="font-bold text-green-800">Lookin for</span>
                            <span class="font-lg text-green-800 capitalize"> {{str_replace('_',' ',$user->relationship_goals?->value)}} </span>
                        </div>
                    </div>

                    <!-- Más información -->
                    <section class="divide-y space-y-2">
                        @if ($user->languages)

                        <div class="space-y-3 py-2">
                            <h3 class="font-bold text-xl py-2">Lenguages i know </h3>
                            <ul class="flex flex-wrap gap-3">
                                @foreach ($user->languages as $language)
                                <li class="border border-gray-500 rounded-2xl text-sm px-2.5 p-1.5 capitalize">{{$language->name}}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if ($user->basics)


                        <div class="space-y-3 py-2">
                            <h3 class="font-bold text-xl py-2">Basics </h3>
                            <ul class="flex flex-wrap gap-3">
                                @foreach ($user->basics as $basic)
                                <li class="border border-gray-500 rounded-2xl text-sm px-2.5 p-1.5">{{$basic->name}}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="space-y-3 py-2">
                            <h3 class="font-bold text-xl py-2">Lifestyle </h3>
                            <ul class="flex flex-wrap gap-3">
                                <li class="border border-gray-500 rounded-2xl text-sm px-2.5 p-1.5">Non Smoker</li>
                                <li class="border border-gray-500 rounded-2xl text-sm px-2.5 p-1.5">Gvm</li>
                                <li class="border border-gray-500 rounded-2xl text-sm px-2.5 p-1.5">Travel</li>
                            </ul>
                        </div>
                    </section>
                </section>

                <!-- <section class="sticky bg-gradient-to-to-white bottom-0 py-2 flex items-center justify-center gap-4 "> -->
                <!-- </section> -->

                <!-- Actions -->

                <section class="sticky bg-gradient-to-b from-white/50 to-white bottom-0 py-2  flex items-center justify-center gap-4 inset-x-0 mx-auto">
                    <!-- swipe left -->
                    <div>
                        <button
                            draggable="true"
                            @click="swipeLeft()"
                            class="bg-white rounded-full border-2 pointer-events-auto group border-red-600 p-2 shrink-0 max-w-fit flex items-center text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                stroke-width="3" stroke="currentColor"
                                class="size-6 w-10 h-10  shrink-0 m-auto group-hover:scale-105 transition-transform ">
                                <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>


                        </button>
                    </div>
                    <!-- Super Like -->
                    <div>
                        <button
                            draggable="true"
                            @click="swipeUp()"
                            class=" bg-white rounded-full border-2 pointer-events-auto group border-blue-600 p-2 shrink-0 max-w-fit flex items-center text-blue-600 scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6 w-10 h-10  shrink-0 m-auto group-hover:scale-105 transition-transform">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <!-- Swipe Right -->
                    <div>
                        <button
                            draggable="true"
                            @click="swipeRight()"
                            class=" bg-white rounded-full border-2 pointer-events-auto group border-green-600 p-2 shrink-0 max-w-fit flex items-center text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6 w-10 h-10  shrink-0 m-auto group-hover:scale-105 transition-transform">
                                <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                            </svg>

                        </button>
                    </div>
                </section>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Match found -->
    <div 
        x-data="{ modalOpen: false }"
        @keydown.escape.window="modalOpen = false"
        @close-match-modal.window="modalOpen=false"
        @match-found.window="modalOpen=true"
        class="relative z-50 w-auto h-auto">
        <template x-teleport="body">
            <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" x-cloak>
                <div x-show="modalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click="modalOpen=false" class="absolute inset-0 w-full h-full bg-black bg-opacity-40"></div>
                <div x-show="modalOpen"
                    x-trap.inert.noscroll="modalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full py-6 bg-white px-7 h-full sm:h-[550px] sm:max-w-lg sm:rounded-lg">

                    <!-- Header -->
                    <div class="items-center justify-between p-2 py-3 block">
                        <button @click="modalOpen=false" class="absolute top-0 right-0 flex items-center justify-center w-8 h-8 mt-5 mr-5 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <!-- Main -->
                    <main class="relative w-auto flex flex-col gap-y-9">
                        <div class="max-auto flex flex-col gap-2 items-center justity-center">
                            <!-- tinder logo -->
                            <div class="max-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-red-500 w-14 h-14" viewBox="0,0,256,256" width="48px" height="48px" fill="currentColor">
                                    <g>
                                        <g transform="scale(10.66667,10.66667)">
                                            <path d="M8.4,9.464c4.05,-1.286 4.693,-5.014 4.179,-8.357c0,0 0,-0.193 0.129,-0.129c3.921,1.929 8.292,5.979 8.292,12.215c0,4.628 -3.664,8.807 -9,8.807c-5.786,0 -9,-4.05 -9,-8.871c0,-2.893 1.929,-5.786 4.179,-7.071c0,0 0.193,0 0.193,0.129c0,0.643 0.257,2.25 0.964,3.214z"></path>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <h5 class="font-bold text-3xl">
                                It's a Match
                            </h5>
                        </div>

                        <!-- show matches -->

                        <div class="flex items-center gap-4 mx-auto">
                            <span>
                                <img src="https://picsum.photos/200/200" alt="" class="rounded-full h-32 w-32 ring ring-rose-500">
                            </span>
                            
                            <span>
                                <!-- <img src="https://picsum.photos/id/2/200/200" alt="" class="rounded-full h-32 w-32"> -->
                                <img src="https://picsum.photos/200/200?" alt="" class="rounded-full h-32 w-32 ring ring-pink-500/40">
                            </span>
                        </div>

                        <!-- Acciones -->
                         <div class="mx-auto flex flex-col gap-5">
                            <button wire:click="createConversation" class="bg-tinder text-white font-bold items-center px-3 py-2 rounded-full">
                                Enviar Mensaje
                            </button>

                            <button @click="modalOpen=false" class="bg-gray-500 text-white font-bold items-center px-3 py-2 rounded-full">
                                Continue Swiping
                            </button>
                         </div>
                    </main>
                </div>
            </div>
        </template>
    </div>
</div>