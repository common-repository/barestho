const BARESTHO_WIDGET_ID = "barestho-widget";

const BARESTHO_BACKGROUND_POPUP_ID = "barestho-popup-background";

const BARESTHO_WIDGET_VIEW_MODES = {
  STANDALONE: "standalone",
  INPAGE: "in-page",
  TOGGLE: "toggle",
  POPUP: "popup",
}

class BaresthoWidgetManager {

  /**
   * @param {"create" | "remove"} action 
   */
  managePopupBackground = (action) => {
    const backgroundDiv = document.getElementById(BARESTHO_BACKGROUND_POPUP_ID);
    if (action === 'create' && !backgroundDiv) {
      const newBackgroundDiv = document.createElement('div');
      newBackgroundDiv.id = BARESTHO_BACKGROUND_POPUP_ID;
      document.body.appendChild(newBackgroundDiv);
      document.body.style.overflow = 'hidden'; 
    } else if (action === 'remove' && backgroundDiv) {
      backgroundDiv.remove();
      document.body.style.overflow = ''; 
    }
  }

  /**
   * @param {string} id 
   */
  openPopup= (id) => {
    const widget = document.querySelector(`.${BARESTHO_WIDGET_ID}.${BARESTHO_WIDGET_VIEW_MODES.POPUP}#${id}`);
    widget?.classList.add("open");
    this.managePopupBackground('create');
  }

  /**
   * Handles iframe messsages.
   * @param {MessageEvent<{ type: string, payload: any }>} e 
   * @param {HTMLIFrameElement} widget 
   */
  handleMessage = (e, widget) => {
    const { type, payload } = e.data;
  
    switch(type) {
      case "popup": {
        widget.classList.remove("open");
        this.managePopupBackground('remove'); 
        break;
      }
      case "toggle": {
        const isOpen = payload.state ?? false;
        if (isOpen && window.innerWidth < 450) {
          widget.classList.add("open")
        } else widget.classList.remove("open");
        break;
      }
      case "resize": {
        widget.style.height = `${payload.height}px`;
        break;
      }
      default:
        console.warn(`Unknown message type "${type}".`);
        break;
    }
  }

  main = () => {
    const widgets = document.querySelectorAll(`.${BARESTHO_WIDGET_ID}`);

    for (const widget of widgets) {
      const src = widget.getAttribute("src");

      const srcUrl = new URL(src);

      const widgetMode = srcUrl.searchParams.get("view") ?? BARESTHO_WIDGET_VIEW_MODES.STANDALONE;

      widget.classList.add(widgetMode);
    }

    // Message dispatcher
    window.addEventListener("message", e => {

      const foundWidget = Array.from(widgets)
        .find(widget => widget.contentWindow === e.source);

      if(foundWidget)
        this.handleMessage(e, foundWidget);
    });
  }
}

var barestho = new BaresthoWidgetManager();

document.addEventListener('DOMContentLoaded', barestho.main);