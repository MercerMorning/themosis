<template>
  <div class="chat-container">
    <div class="menu-threads">
      <div class="menu-threads__current-user">
        <a class="menu-threads__link current-user">
          <img class="thread-link__participant_ava" src="/content/themes/myinvision/assets/images/person.png">
          <div class="thread-link__current-user_name">
            {{ usersData[usersData.current_user_id].first_name + ' ' +  usersData[usersData.current_user_id].last_name}}
          </div>
        </a>
      </div>
      <ul class="menu-threads__list">
        <li v-for="thread in threadsData"
            v-bind:class="[thread.id === JSON.parse(currentThread)
               ? 'menu-threads__item active'
               : 'menu-threads__item']">

          <a v-bind:data-id="thread.id" class="menu-threads__link" v-on:click="openThread">
            <div class="thread-link__dialog">
              <div class="thread-link__content">
                <span class="thread-participant_name">{{ thread.subject }}</span>
                <span class="thread-participant_message">{{ thread.last_message }}</span>
              </div>
              <div class="thread-link__date-time">
                <span>{{ thread.created_at }}</span>
              </div>
            </div>
          </a>
        </li>
<!--        <li class="menu-threads__item active">-->
<!--          <a class="menu-threads__link">-->
<!--            <img class="thread-link__participant_ava" src="/content/themes/myinvision/assets/images/person.png">-->
<!--            <div class="thread-link__dialog">-->
<!--              <div class="thread-link__content">-->
<!--                <span class="thread-participant_name">Phillip Torff</span>-->
<!--                <span class="thread-participant_message">Thank you, Phillip!</span>-->
<!--              </div>-->
<!--              <div class="thread-link__date-time">-->
<!--                <span>17/06/2020</span>-->
<!--              </div>-->
<!--            </div>-->
<!--          </a>-->
<!--        </li>-->
<!--        <li class="menu-threads__item">-->
<!--          <a class="menu-threads__link">-->
<!--            <img class="thread-link__participant_ava" src="/content/themes/myinvision/assets/images/person.png">-->
<!--            <div class="thread-link__dialog">-->
<!--              <div class="thread-link__content">-->
<!--                <span class="thread-participant_name">Phillip Torff</span>-->
<!--                <span class="thread-participant_message">Thank you, Phillip!</span>-->
<!--              </div>-->
<!--              <div class="thread-link__date-time">-->
<!--                <span>17/06/2020</span>-->
<!--              </div>-->
<!--            </div>-->
<!--          </a>-->
<!--        </li>-->
      </ul>
    </div>
    <div class="chat mobile-hidden">
      <div class="chat__participant-chat">
        <svg width="20" height="18">
          <use xlink:href="/content/themes/myinvision/assets/images/previous.svg#previous"></use>
        </svg>
        <span>Phillip Torff</span>
        <img class="" src="/content/themes/myinvision/assets/images/person.png">
      </div>
      <div class="chat-body">

        <label v-for="(messages, date) in threadMessages">
          <div class="chat-message__date">
                <span>
                    {{ date }}
                </span>

          </div>
          <div v-for="message in messages"
               v-bind:class="[message.user_id === usersData.current_user_id
               ? 'chat-message__message chat-message__message_current-user'
               : 'chat-message__message chat-message__message_foreign-user']">
            <span v-if="message.user_id !== usersData.current_user_id" class="chat-message__autor">
              {{ usersData[message.user_id].first_name + ' ' + usersData[message.user_id].last_name }}
            </span>
            <div class="chat-message__message_message-content">
              <img v-if="message.user_id !== usersData.current_user_id" class="thread-link__participant_ava" src="/content/themes/myinvision/assets/images/person.png">
              <div class="chat-message__user-messages">
                <div class="chat-message__message_message-body">
                  <div class="chat-message__text">
                    {{ message.body }}
                  </div>
                  <div class="chat-message__time">{{ message.created_at }}</div>
                </div>
              </div>

            </div>
          </div>
        </label>
      </div>
      <form class="chat-footer" v-on:submit.prevent="sendMessage">
        <div class="chat-footer__input-wrpa">
          <input type="hidden" name="threadId" v-bind:value="currentThread">
          <label class="chat-footer__input-file">
            <svg width="20" height="18">
              <use xlink:href="/content/themes/myinvision/assets/images/sprite.svg#icon-clip"></use>
            </svg>
            <input type="file" name="file">
          </label>
          <label class="chat-footer__input-text">
            <textarea class="input-message" type="text" name="body" placeholder="Введите сообщение"></textarea>
          </label>

          <label class="chat-footer__send-message">
            <button class="button chat-footer__button" type="submit">
                            <span class="send-button_text">
                                Отправить
                            </span>
            </button>
            <svg width="20" height="18">
              <use xlink:href="/content/themes/myinvision/assets/images/send.svg#send"></use>
            </svg>
          </label>
        </div>
      </form>
    </div>

  </div>
</template>


<script>
function getCookie(name) {
  let matches = document.cookie.match(new RegExp(
      "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : JSON.stringify(null);
}

function setCookie(name, value, options = {}) {

  options = {
    path: '/',
    // при необходимости добавьте другие значения по умолчанию
    ...options
  };

  if (options.expires instanceof Date) {
    options.expires = options.expires.toUTCString();
  }

  let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

  for (let optionKey in options) {
    updatedCookie += "; " + optionKey;
    let optionValue = options[optionKey];
    if (optionValue !== true) {
      updatedCookie += "=" + optionValue;
    }
  }

  document.cookie = updatedCookie;
}

export default {
  mounted: function(){
    setInterval(function(){
        axios.get('chat/get_thread?thread_id=' + JSON.parse(getCookie('currentThread'))).then( response => {
          setCookie('threadMessages',  JSON.stringify(response.data));
        });
    }, 1000);
  },
  props: {
    threads: String,
    users: String,
    currentUser: String,
  },
  data: function () {
    {
      return {
        threadsData: JSON.parse(this.threads),
        usersData: JSON.parse(this.users),
        currentThread: JSON.parse(getCookie('currentThread')) ?? null,
        threadMessages: JSON.parse(getCookie('threadMessages')) ?? null,
      }
    }
  },
  methods: {
    getThreads: function () {
      return fetch('/')
    },
    sendMessage: function () {
      event.preventDefault();
      axios.post('chat/send_message_to_thread/',  {
        'body' : new FormData(event.target).get('body'),
        'thread_id' : new FormData(event.target).get('threadId')
      }).then(response => {
        this.threadMessages = response.data
      })
    },
    addThread: function (event) {
      event.preventDefault();
      axios.post('chat/create_thread/',  {
        'name' : new FormData(event.target).get('name')
      }).then( response => {
        this.threadsData = response.data
      })
    },
    openThread: function (event) {
      console.log(event.target.dataset.id)
      axios.get('chat/get_thread?thread_id=' +  event.target.dataset.id).then( response => {
        this.threadMessages = response.data
        this.currentThread = event.target.dataset.id;
        setCookie('currentThread',  JSON.stringify(event.target.dataset.id))
        setCookie('threadMessages',  JSON.stringify(response.data))
      })

      console.log(this. threadMessages);
    }
  }
}


</script>
