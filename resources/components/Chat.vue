<template>
  <div class="chat-container">
    <div class="menu-threads">
      <div class="menu-threads__current-user">
        <a class="menu-threads__link current-user">
          <img class="thread-link__participant_ava" v-bind:src="currentUserData.ava">
          <div class="thread-link__current-user_name">
            {{ currentUserData.first_name + ' ' +  currentUserData.last_name}}
          </div>
        </a>
      </div>
      <ul class="menu-threads__list">
        <li v-for="thread in threadsData"
            v-on:click="showThread"
            v-bind:data-id="thread.id"
            v-bind:class="[thread.id === currentThread.id
               ? 'menu-threads__item active'
               : 'menu-threads__item']">
          <a class="menu-threads__link">
<!--            <img class="thread-link__participant_ava" src="/content/themes/myinvision/assets/images/person.png">-->
            <div class="thread-link__dialog">
              <div class="thread-link__content">
                <span class="thread-participant_name">{{ thread.subject }}</span>
                <span class="thread-participant_message">{{ thread.lastMessage }}</span>
              </div>
              <div class="thread-link__date-time">
                <span>{{ thread.datetime }}</span>
              </div>
            </div>
          </a>
        </li>
      </ul>
    </div>
    <div class="chat mobile-hidden">
      <div class="chat__participant-chat">
        <svg width="20" height="18">
          <use xlink:href="/content/themes/myinvision/assets//images/previous.svg#previous"></use>
        </svg>
        <span>Phillip Torff</span>
        <img class="" src="/content/themes/myinvision/assets/images/person.png">
      </div>
      <div class="chat-body">

        <label v-for="(groupMessages, date) in threadMessages">
          <div class="chat-message__date">
                <span>
                    {{ date }}
                </span>
          </div>
          <div v-for="groupMessage in groupMessages"
               v-bind:class="[groupMessage.user_info.user_id === currentUserData.id
               ? 'chat-message__message chat-message__message_current-user'
               : 'chat-message__message chat-message__message_foreign-user']">
            <span v-if="groupMessage.user_info.user_id !== currentUserData.id" class="chat-message__autor">
              {{ groupMessage.user_info.first_name + ' ' + groupMessage.user_info.last_name }}
            </span>
            <div class="chat-message__message_message-content">
              <img class="thread-link__participant_ava"
                   v-bind:src="groupMessage.user_info.ava">
              <div class="chat-message__user-messages">
                <div v-for="message in groupMessage.messages" class="chat-message__message_message-body">
                  <div class="chat-message__text">
<!--                    <img class="message_image" v-if="message.is_file" v-bind:src="message.body">-->
                    <span>{{ message.body }}</span>
                  </div>
                  <div class="chat-message__time">{{ message.created_at }}</div>
                </div>
              </div>

            </div>
          </div>
        </label>
      </div>
      <form class="chat-footer" v-on:submit.prevent="sendMessage">
        <input type="hidden" name="threadId" v-bind:value="currentThread.id">
        <div class="chat-footer__input-wrpa">
          <label class="chat-footer__input-file">
            <svg width="20" height="18">
              <use xlink:href="/content/themes/myinvision/assets//images/sprite.svg#icon-clip"></use>
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
              <use xlink:href="/content/themes/myinvision/assets//images/send.svg#send"></use>
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

    const socket = new WebSocket("ws://localhost:8999");

    // socket.onopen = () => {
    //   socket.send("Hello!");
    // };

    socket.onmessage = (response) => {
      let parsedResponse = JSON.parse(response.data);
      this.threadsData = parsedResponse.threads
      this.currentThread = parsedResponse.currentThread;
      this.threadMessages = parsedResponse.threadMessages;
    };
  },
  props: {
    threads: String,
    currentuser: String,
    currentthread: String,
    threadmessages: String,
    usertoken: String,
  },
  data: function () {
    {
      return {
        socket : new WebSocket("ws://localhost:8999"),
        threadsData: JSON.parse(this.threads),
        currentUserData: JSON.parse(this.currentuser),
        currentThread: JSON.parse(this.currentthread) ?? null,
        threadMessages: JSON.parse(this.threadmessages) ?? null,
        userToken: JSON.parse(this.usertoken) ?? null
      }
    }
  },
  methods: {
    showThread : function (e) {
      let idItem = event.target.closest('.menu-threads__item');
      axios.get('/chat/get_thread?id=' +  idItem.dataset.id).then( response => {
        document.cookie = "currentThreadId=" + response.data.currentThread.id;
        this.threadsData = response.data.threads
        this.currentThread = response.data.currentThread;
        this.threadMessages = response.data.threadMessages;
      })
    },
    sendMessage: function (event) {
      let formData = new FormData(event.target);
        this.socket.send(JSON.stringify({
          user_id: this.currentUserData.id,
          thread_id: formData.get('threadId'),
          body: formData.get('body'),
          token: this.userToken,
        }));
      document.querySelector('.chat-footer').reset();

      // }

      // axios.post('/chat/send_message_to_thread/',  {
      //   'body' : formData.get('body'),
      //   'thread_id' : formData.get('threadId'),
      //   // 'file' : 'sdf'
      // }).then(response => {
      //   document.querySelector('.chat-footer').reset();
      //   this.threadMessages = response.data
      // })

    },
  }

}


</script>
