<section id="contact" class="contact section gradient-overlay-black">
  <div class="overlay-inner">
    <div class="container">
      <!--|Section Header|-->
      <header class="section-header text-center wow flipInX" data-wow-delay=".15s">
        <div class="row">
          <div class="col-6 block-center">
            <h2 class="section-title">Contato</h2>
            <p>Dúvidas? Entre em contato agora mesmo!</p>
          </div>
        </div>
      </header> <!--|End Section Header|-->

      <div class="row">
        <div class="col-md-8 block-center">
          <!--|Contact Form|-->
          <form class="contact-form wow shake" method="POST" action="/sendLandingContactForm" data-wow-delay=".15s">
          {!! csrf_field() !!}
            <!--|Action Message|-->


            <div class="entry-field">
                <input id="name" name="name" placeholder="Nome" required="" type="text">
            </div>
            <div class="entry-field">
                <input id="email" name="email" placeholder="email@exemplo.com" required="" type="email">
            </div>
            <div class="entry-field">
                <input id="subject" name="subject" placeholder="Assunto" type="text">
            </div>
            <div class="entry-field">
                <textarea id="message" rows="6" name="message" placeholder="Deixe sua mensagem" required=""></textarea>
            </div>

            <div class="action-message">
                <p class="alert-info contact-sending">Enviando sua mensagem, um minuto.</p>
                <p class="alert-success contact-success">Sua mensagem foi enviada!</p>
                <p class="alert-danger contact-error">Opps!! Para que você não preencheu o formulário corretamente.</p>
            </div>

            <div class="text-center">
                <button id="submit" class="btn btn-lg btn-primary" type="submit"><i class="ion-ios-paperplane"></i> Enviar</button>
            </div>
          </form> <!--|End Contact Form|-->
        </div>
      </div>
    </div>
  </div>
</section>