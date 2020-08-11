(function ($, Drupal) {
  $(function () {
    var email = $('#email');
    var submit = $('#submit');
    var watch = $('#watch');
    var watch_box = $('#watch-box');
    var cricle = $('#cricle');
    var loading = false;
    var flag = null;

    submit.on('click', function () {
      if (loading) return false;
      loading = true;
      var val = email.val();
      if (/^([a-zA-Z0-9._-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/.test(val)) {
        $.post('', {email: val}, function () {
          loading = false;
          swal("Thank Your", 'We have received your email', 'success')
        })
      } else {
        swal("Oops", "Email validation failed !", "error")
      }
    });
    // 时钟
    let p = 305 / 543;
    let p1 = 20 / 543;


    const sHand = document.querySelector('.s');
    const mHand = document.querySelector('.m');
    const hHand = document.querySelector('.h');

    function setLayout() {
      watch_box.css({
        width: watch.width() * p + 'px',
        height: watch.width() * p + 'px',
        display: 'block'
      })
      cricle.css({
        width: watch.width() * p1 + 'px',
        height: watch.width() * p1 + 'px',
      })
    }

    function setTime() {
      const d = new Date();
      const s = d.getSeconds();
      const m = d.getMinutes();
      const h = d.getHours();

      const sDeg = ((s / 60) * 360);
      const mDeg = ((m / 60) * 360);
      const hDeg = (((h / 12) + 1) * 360);

      sHand.style.transform = `rotate( ${sDeg}deg )`;
      mHand.style.transform = `rotate( ${mDeg}deg )`;
      hHand.style.transform = `rotate( ${hDeg}deg )`;
    }

    setTime();
    setLayout();

    flag = setInterval(setTime, 1000);
    $(window).resize(function () {
      setLayout()
    });
  });
})(jQuery, Drupal)
