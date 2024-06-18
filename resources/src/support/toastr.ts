import Toastify, { type Options } from "toastify-js";
import { Colors } from "../master.css";
import "toastify-js/src/toastify.css";

type ToastrOptions = Pick<
  Options,
  "duration" | "close" | "gravity" | "position"
>;

const ToastrDefaultOptions = {
  escapeMarkup: false,
  duration: 5000,
  gravity: "top" as Options["gravity"],
  position: "center" as Options["position"],
};

const BaseStyle = {
  padding: "15px",
  "font-weight": "bold",
  "border-radius": "5px",
};

export default class Toastr {
  /**
   * Show a success toast
   *
   * @param message
   * @param options
   */
  public success(message: string, options: ToastrOptions = {}) {
    const toast = Toastify({
      text: message,
      onClick: () => {
        if (options.close ?? true) {
          toast.hideToast();
        }
      },
      style: {
        background: Colors.Success,
        ...BaseStyle,
      },
      ...ToastrDefaultOptions,
      ...options,
    });

    toast.showToast();
  }

  /**
   * Show an error toast
   *
   * @param message
   * @param options
   */
  public error(message: string, options: ToastrOptions = {}) {
    const toast = Toastify({
      text: message,
      onClick: () => {
        if (options.close ?? true) {
          toast.hideToast();
        }
      },
      style: {
        background: Colors.Error,
        ...BaseStyle,
      },
      ...ToastrDefaultOptions,
      ...options,
    });

    toast.showToast();
  }
}
