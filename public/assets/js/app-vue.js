
var appVue = new Vue({
    el: '#app',
    data: {
        message: { "test": "Default Message" },
        pageTitles: { "home": "Home" },

        sidebarVisibility: false, // True - Hidden, False - Showed
        pageLoaderVisibility: false, // True - Showed, False - Hidden
        quickNotifiVisibility: true, // True - Hidden, False - Showed
        bodyVisibility: false,

        windowWid: window.innerWidth, // realtime full page width
        loadedWindowWid: window.innerWidth, // full page width on page load.
    },

    methods: {
        toggleSidebar: function() {
            const buttonClicked = document.getElementById("app-sidebar-toggle")
            setTimeout(() => buttonClicked.blur(), 50)
            !this.sidebarVisibility ? this.sidebarVisibility = true : this.sidebarVisibility = false
        },
        toggleQuickNotifi: function() {
            const buttonClicked = document.getElementById("quicknotifi-toggle")
            setTimeout(() => buttonClicked.blur(), 50)
            if(this.quickNotifiVisibility) {
                this.quickNotifiVisibility = false;
            } else {
                this.quickNotifiVisibility = true;
            }
        },
        autoSidebarVisibility: function() {
            if(this.loadedWindowWid < 480) {
                this.sidebarVisibility = true
                console.log("runned")
            }
        },  
        toggleLoader: function() {
            if(!this.pageLoaderVisibility) {
                this.pageLoaderVisibility = true
            } else {
                this.pageLoaderVisibility = false
            }
        },
        updateMessages: function() {
            if(message != null) {
                this.message = message;
            }
        }
    },
    
    beforeMount() {
        this.updateMessages();
    },

    mounted() {
        this.autoSidebarVisibility();
        window.addEventListener("resize", () => {
            this.windowWid = window.innerWidth
        })
    },  
    
  });   
