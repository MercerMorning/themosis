<template>
  <div class="chat-container">
    <div class="menu-threads">
      <div class="menu-threads__current-user">
        <a class="menu-threads__link current-user">
          <img class="thread-link__participant_ava" src="/content/themes/myinvision/assets/images/person.png">
          <div class="thread-link__current-user_name">
            Phillip Torff
          </div>
        </a>
      </div>
      <ul class="menu-threads__list">
        <li v-for="thread in threadsData" class="menu-threads__item">
          <a v-bind:data-id="thread.id" class="menu-threads__link">
            <div class="thread-link__dialog">
              <div class="thread-link__content">
                <span class="thread-participant_name">{{ thread.subject }}</span>
                <span class="thread-participant_message">{{ thread.lastMessage }}</span>
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
        <div class="chat-message__date">
                <span>
                    17.06.2021
                </span>
        </div>
        <div class="chat-message__message chat-message__message_foreign-user">
          <span class="chat-message__autor">Phillip Torff</span>
          <div class="chat-message__message_message-content">
            <img class="thread-link__participant_ava" src="/content/themes/myinvision/assets/images/person.png">
            <div class="chat-message__user-messages">
              <div class="chat-message__message_message-body">
                <div class="chat-message__text">
                  Хай
                </div>
                <div class="chat-message__time">11:53</div>
              </div>
              <div class="chat-message__message_message-body">
                <div class="chat-message__text">
                  Хай
                </div>
                <div class="chat-message__time">11:53</div>
              </div>
            </div>

          </div>
        </div>
        <div class="chat-message__message chat-message__message_current-user">
          <div class="chat-message__message_message-content">
            <div class="chat-message__user-messages">
              <div class="chat-message__message_message-body">
                <div class="chat-message__text">
                  Хай
                </div>
                <div class="chat-message__time">11:53</div>
              </div>
              <div class="chat-message__message_message-body">
                <div class="chat-message__text">
                  Хай
                </div>
                <div class="chat-message__time">11:53</div>
              </div>
            </div>
            <img class="thread-link__participant_ava" src="/content/themes/myinvision/assets/images/person.png">
          </div>
        </div>
      </div>
      <form class="chat-footer">
        <div class="chat-footer__input-wrpa">
          <label class="chat-footer__input-file">
            <svg width="20" height="18">
              <use xlink:href="/content/themes/myinvision/assets/images/sprite.svg#icon-clip"></use>
            </svg>
            <input type="file" name="file">
          </label>
          <label class="chat-footer__input-text">
            <textarea class="input-message" type="text" name="text" placeholder="Введите сообщение"></textarea>
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
export default {
  mounted: function(){
    // console.log(this.token)
    console.log("Hello Vue!");
    // console.log(this.threads());
  },
  props: {
    threads: String,
    users: String,
  },
  data: function () {
    {
      return {
        threadsData: JSON.parse(this.threads),
        usersData: JSON.parse(this.users),
        threadBody: null,
        currentThread: null,
        threadMessages: null,
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
      axios.get('chat/get_thread?thread_id=' +  event.target.dataset.id).then( response => {
        console.log(response.data)
        this.threadMessages = response.data
        this.currentThread = event.target.dataset.id;
      })
    }
  }
}


</script>
