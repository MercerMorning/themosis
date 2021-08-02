<template>
  <div class="chat-container">
    <div
         v-bind:class="[createNewTheme
               ? 'popups active'
               : 'popups']">
        <div class="popups__container-wrap">
          <button class="popups__close" v-on:click="closeCreateThemeModal">
            <svg width="20" height="20">
              <use xlink:href="/content/themes/myinvision/assets/images/close.svg#icon-close"></use>
            </svg>
          </button>
          <div class="popups__container">
            <h2 class="popups__h">Создайте новую тему</h2>
            <form action="#" method="post" class="popups__inputs" v-on:submit.prevent="createNewThemeFunc">
              <label class="input">
                <span class="input__placeholder" required>Название темы*</span>
                <input type="text" name="name">
              </label>
              <label class="input">
                <span class="input__placeholder" required>Участники</span>
                <select class="theme_participants_input" name="participants_id" multiple>
                  <option v-for="user in usersData"
                          v-bind:value="user.ID"
                          v-bind:selectvalue="user.ID">
                      {{ user.first_name + ' ' + user.last_name }}
                  </option>
                </select>
              </label>
              <input type="submit" value="Создать">
            </form>
          </div>
        </div>
        <div class="popups__close-area" data-popups-close=""></div>
      </div>
    <div class="menu-threads">
      <div class="menu-threads__current-user">
        <a class="menu-threads__link current-user">
          <img class="thread-link__participant_ava"
               v-bind:src="usersData[usersData.current_user_id].ava">
          <div class="thread-link__current-user_name">
            {{ usersData[usersData.current_user_id].first_name + ' ' +  usersData[usersData.current_user_id].last_name}}
          </div>
        </a>
      </div>
      <ul class="menu-threads__list">
        <li class="menu-threads__item">
          <a class="menu-threads__link" v-on:click="openCreateThemeModal">
            Создать новую тему
          </a>
        </li>
        <li v-for="thread in threadsData"
            v-bind:class="[thread.id === JSON.parse(currentThread)
               ? 'menu-threads__item active'
               : 'menu-threads__item']">

          <a v-bind:data-id="thread.id"
             v-bind:data-participantId="thread.participant_id"
             class="menu-threads__link"
             v-on:click="openThread">
            <img v-if="thread.participant_id"
                 class="thread-link__participant_ava"
                 v-bind:src="usersData[thread.participant_id].ava">
            <div class="thread-link__dialog">
              <div class="thread-link__content">
                <span class="thread-participant_name">
                  {{ thread.participant_id
                    ? thread.first_name + ' ' + thread.last_name
                    : thread.subject }}
                </span>
                <span class="thread-participant_message">{{ thread.last_message }}</span>
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
          <use xlink:href="/content/themes/myinvision/assets/images/previous.svg#previous"></use>
        </svg>
        <span>Phillip Torff</span>
        <img class=""
             v-bind:src="usersData[usersData.current_user_id].ava">
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
              <img class="thread-link__participant_ava"
                   v-bind:src="usersData[message.user_id].ava">
              <div class="chat-message__user-messages">
                <div class="chat-message__message_message-body">
                  <div class="chat-message__text">
                    <img class="message_image" v-if="message.is_file" v-bind:src="message.body">
                    <span v-else>{{ message.body }}</span>
                  </div>
                  <div class="chat-message__time">{{ message.created_at }}</div>
                </div>
              </div>

            </div>
          </div>
        </label>
      </div>
      <form enctype="multipart/form-data" class="chat-footer" v-on:submit.prevent="sendMessage">
        <div class="chat-footer__input-wrpa">
          <input type="hidden" name="threadId" v-bind:value="currentThread">
          <label class="chat-footer__input-file">
            <svg width="20" height="18">
              <use xlink:href="/content/themes/myinvision/assets/images/sprite.svg#icon-clip"></use>
            </svg>
            <input type="file" class="input-file" name="file" v-on:change="sendImage">
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
      this.invervalRecievingThreadData()
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
        createNewTheme: false,
        newThread: null
      }
    }
  },
  methods: {
    getThreads: function () {
      return fetch('/')
    },
    sendMessage: function (event) {

      let formFile = new FormData(event.target).get('file') ?? new FormData(event.target).get('file');

      console.log(formFile.size)
      if (formFile.size !== 0) {
        var reader = new FileReader();
        reader.readAsDataURL(formFile);
        reader.onload = function () {
          axios.post('/chat/send_message_to_thread/', {
            'body': reader.result,
            'thread_id': new FormData(event.target).get('threadId'),
            // 'file' : 'sdf'
          }).then(response => {
            console.log(response.data)
            // this.threadMessages = response.data
          })
        }
      } else {
        axios.post('/chat/send_message_to_thread/',  {
          'body' : new FormData(event.target).get('body'),
          'thread_id' : new FormData(event.target).get('threadId'),
          // 'file' : 'sdf'
        }).then(response => {
          this.threadMessages = response.data
        })
      }
      document.querySelector('.chat-footer').reset()

    },
    // addThread: function (event) {
    //   event.preventDefault();
    //   axios.post('/chat/create_thread/',  {
    //     'name' : new FormData(event.target).get('name')
    //   }).then( response => {
    //     this.threadsData = response.data
    //   })
    // },
    openThread: function (event) {
      document.querySelector('.chat-footer').reset()
      if (event.target.classList == 'menu-threads__link') {
        if (!event.target.dataset.id) {
          console.log(event.target.dataset.participantid)
          axios.post('/chat/create_thread/',  {
            'participant_id' : event.target.dataset.participantid
          }).then( response => {
            this.threadsData = response.data
            let newThreadId = response.data.new_thread_id
            axios.get('/chat/get_thread?thread_id=' +  newThreadId).then( response => {
              this.threadMessages = response.data
              this.currentThread = newThreadId;
              setCookie('currentThread',  JSON.stringify(newThreadId))
              setCookie('threadMessages',  JSON.stringify(response.data))
            })
          })

        } else {
          axios.get('/chat/get_thread?thread_id=' +  event.target.dataset.id).then( response => {
            this.threadMessages = response.data
            this.currentThread = event.target.dataset.id;
            setCookie('currentThread',  JSON.stringify(event.target.dataset.id))
            setCookie('threadMessages',  JSON.stringify(response.data))
          })
        }
      }

    },
    invervalRecievingThreadData: function () {
        setInterval(() => {
          axios.get('/chat/get_thread?thread_id=' +  JSON.parse(getCookie('currentThread'))).then( response => {
            this.threadMessages = response.data
            this.currentThread = JSON.parse(getCookie('currentThread'));
          }).catch(function (error) {
            console.log(error)
          })
          axios.get('/chat/get_threads').then( response => {
            this.threadsData = response.data
          }).catch(function (error) {
            console.log(error)
          })
        }, 3000)

    },
    openCreateThemeModal()
    {
      this.createNewTheme = true;
      jQuery('.theme_participants_input').select2();
    },
    closeCreateThemeModal()
    {
      this.createNewTheme = false;
    },
    createNewThemeFunc: function (event) {
      axios.post('/chat/create_thread/',  {
        'name' : new FormData(event.target).get('name')
      }).then( response => {
        // this.threadsData = response.data
        this.newThread = response.data.new_thread_id
        let participantIds;
        participantIds = jQuery('.theme_participants_input').find(':selected').map((itemId, element) => {
            return element.value
        })
        // participantIds = jQuery('.theme_participants_input').find(':selected')
        // participantIds = jQuery('.theme_participants_input').find(':selected').data('');
        participantIds = participantIds.toArray()
        axios.post('/chat/invite_to_thread/',  {
          'thread_id' : this.newThread,
          'participants_id' : participantIds
        }).then( response => {
          // console.log(new FormData(event.target).get('participants_id'))
          console.log(response.data)
          this.threadsData = response.data
        })
        this.newThread = null
        this.createNewTheme = false;
      })

    },
    sendImage: function()
    {
      // console.log(document.querySelector('.chat-footer'));
      let formData = new FormData(document.querySelector('.chat-footer'));
      if (formData.get('file').size !== 0) {
        var reader = new FileReader();
        reader.readAsDataURL(formData.get('file'));
        reader.onload = function () {
          console.log(reader.result);
          axios.post('/chat/send_message_to_thread/', {
            'image': reader.result,
            'thread_id': formData.get('threadId'),
            // 'file' : 'sdf'
          }).then(response => {
            // console.log(response.data)
            // this.threadMessages = response.data
          })
        }
      }
      document.querySelector('.chat-footer').reset()
    }
  }
}


</script>
