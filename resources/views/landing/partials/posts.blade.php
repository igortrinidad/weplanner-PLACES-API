<section id="review" class="reviews section overlay-black wow fadeIn" data-wow-delay=".15s">
  <div class="overlay-inner">
    <div class="container">

      <div class="row text-center">
        <h1>Blog We Places</h1>
        <h4>últimos posts</h4>
      </div>
      <div class="row">
        <div class="col-md-7 block-center">

        

          <div class="review-carousel">

            @foreach($posts as $post)
            <div class="review-wrap">
              <a href="https://blog.weplaces.com.br/{{$post->post_name}}" target="_blank">
              <div class="review">
                <div class="text">
                  <h3>{{ $post->post_title }}</h3>
                </div>

                <figure class="review-meta">
                  <!--<img class="avatar" src="assets/images/avatar01.jpg" alt=""> -->
                  <figcaption class="info">
                    <h6 class="name">{{ $post->post_date }}</h6>
                  </figcaption>
                </figure>
              </div>
              </a>
            </div>
            @endforeach

          </div>
        </div>
      </div>
    </div>
  </div>
</section>