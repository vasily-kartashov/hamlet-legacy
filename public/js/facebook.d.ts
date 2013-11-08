/**
 * Copyright (c) 2013 Vasily Kartashov <info@kartashov.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

interface FacebookWindow extends Window {
    fbAsyncInit: () => void;
}

declare module Facebook {

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.Event.subscribe/
     */
    interface LogoutStatusResponse {
        status: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.Event.subscribe/
     */
    interface PromptStatusResponse {
        url: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.Event.subscribe/
     */
    interface EdgeStatusResponse {
        href: string;
        widget: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.Event.subscribe/
     */
    interface CommentStatusResponse {
        href: string;
        commentID: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/
     */
    interface Event {

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Event.subscribe/
         */
            subscribe: {
            (event: string, callback: (response: any) => void): void;
            (event: "auth.login", callback: (response: LoginStatusResponse) => void): void;
            (event: "auth.authResponseChange", callback: (response: LoginStatusResponse) => void): void;
            (event: "auth.statusChange", callback: (response: LoginStatusResponse) => void): void;
            (enent: "auth.logout", callback: (response: LogoutStatusResponse) => void): void;
            (event: "auth.prompt", callback: (response: PromptStatusResponse) => void): void;
            (event: "xfbml:render", callback: () => void): void;
            (event: "edge.create", callback: (response: EdgeStatusResponse) => void): void;
            (event: "edge.remove", callback: (response: EdgeStatusResponse) => void): void;
            (event: "comment.create", callback: (response: CommentStatusResponse) => void): void;
            (event: "comment.remove", callback: (response: CommentStatusResponse) => void): void;
            (event: "message.sent", callback: (url: string) => void): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Event.unsubscribe/
         */
            unsubscribe: {
            (event: string, callback: (response: any) => void): void;
            (event: "auth.login", callback: (response: LoginStatusResponse) => void): void;
            (event: "auth.authResponseChange", callback: (response: LoginStatusResponse) => void): void;
            (event: "auth.statusChange", callback: (response: LoginStatusResponse) => void): void;
            (enent: "auth.logout", callback: (response: LogoutStatusResponse) => void): void;
            (event: "auth.prompt", callback: (response: PromptStatusResponse) => void): void;
            (event: "xfbml:render", callback: () => void): void;
            (event: "edge.create", callback: (response: EdgeStatusResponse) => void): void;
            (event: "edge.remove", callback: (response: EdgeStatusResponse) => void): void;
            (event: "comment.create", callback: (response: CommentStatusResponse) => void): void;
            (event: "comment.remove", callback: (response: CommentStatusResponse) => void): void;
            (event: "message.sent", callback: (url: string) => void): void;
        };
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/
     */
    interface XFBML {

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.XFBML.parse/
         */
            parse: {
            (): void;
            (element: HTMLElement): void;
            (element: HTMLElement, callback: () => void): void;
        };
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.setSize/
     */
    interface Size {
        width: number;
        height: number;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.setDoneLoading/
     */
    interface TimerResult {
        time_delta_ms: number;
        type: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.setUrlHandler/
     */
    interface UrlHandlerData {
        path: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/
     */
    interface Canvas {

        Prefetcher: Prefetcher;

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.hideFlashElement/
         */
            hideFlashElement: {
            (): void;
            (elem: HTMLElement): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.scrollTo/
         */
            scrollTo: {
            (x: number, y: number): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.setAutoGrow/
         */
            setAutoGrow: {
            (onOrOff: boolean): void;
            (interval: number): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.setDoneLoading/
         */
            setDoneLoading: {
            (): void;
            (result: TimerResult): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.setSize/
         */
            setSize: {
            (): void;
            (size: Size): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.setUrlHandler/
         */
            setUrlHandler: {
            (onUrl: (data: UrlHandlerData) => void): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.showFlashElement/
         */
            showFlashElement: {
            (): void;
            (elem: HTMLElement): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.startTimer/
         */
            startTimer: {
            (): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.stopTimer/
         */
            stopTimer: {
            (): void;
            (callback: (result: TimerResult) => void): void;
        };
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/
     */
    interface Prefetcher {

        COLLECT_AUTOMATIC: string;
        COLLECT_MANUAL: string;

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.Prefetcher.addStaticResource/
         */
            addStaticResource: {
            (url: string): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.Canvas.Prefetcher.setCollectionMode/
         */
            setCollectionMode: {
            (mode: string): void;
        };
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.getLoginStatus/#response_and_session_objects
     */
    interface AuthResponse {
        accessToken: string;
        expiresIn: string;
        signedRequest: string;
        userID: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.getLoginStatus/#response_and_session_objects
     */
    interface LoginStatusResponse {
        status: string;
        authResponse?: AuthResponse;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.login/#params
     */
    interface LoginOptions {
        scope?: string;
        enable_profile_selector?: boolean;
        profile_selector_ids?: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/dialogs/feed/
     * @todo not sure the list is complete
     */
    interface FeedDialogParams {
        method: string;
        display?: string;
        from?: string;
        to?: string;
        link?: string;
        picture?: string;
        source?: string;
        name?: string;
        caption?: string;
        description?: string;
        properties?: string;
        actions?: string;
        ref?: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/dialogs/feed/
     */
    interface FeedDialogResponse {
        post_id: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/dialogs/requests/
     */
    interface RequestsDialogParams {
        method: string;
        to?: string;
        filters?: string;
        exclude_ids?: string;
        max_recipients?: number;
        data?: string;
        title?: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/dialogs/requests/
     */
    interface RequestDialogResponse {
        request: string;
        to: string[];
    }

    /**
     * https://developers.facebook.com/docs/reference/dialogs/send/
     */
    interface SendDialogParams {
        method: string;
        display?: string;
        to?: string;
        link?: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/dialogs/friends/
     */
    interface FriendsDialogParams {
        method: string;
        display?: string;
        id?: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/dialogs/friends/
     */
    interface FriendsDialogResponse {
        action: boolean;
    }

    /**
     * https://developers.facebook.com/docs/concepts/payments/dialog/
     */
    interface PayDialogParams {
        method: string;
        action: string;
        product: string;
        quantity?: number;
        quantity_min?: number;
        quantity_max?: number;
        request_id?: string;
        pricepoint_id?: string;
        test_currency?: string;
    }

    /**
     * https://developers.facebook.com/docs/concepts/payments/dialog/
     */
    interface PayDialogResponse {
        payment_id?: number;
        quantity?: string;
        status?: string;
        signed_request?: string;
        request_id?: string;
        error_code?: number;
        error_message?: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/dialogs/add_to_page/
     */
    interface AddPageDialogParams {
        method: string;
        redirect_uri?: string;
        display?: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/FB.init/
     */
    interface InitOptions {
        appId?: string;
        cookie?: boolean;
        logging?: boolean;
        status?: boolean;
        xfbml?: boolean;
        channelUrl?: string;
        authResponse?: AuthResponse;
        frictionlessRequests?: boolean;
        hideFlashCallback?: (info: FlashInfo) => void;
    }

    /**
     * https://developers.facebook.com/docs/howtos/hideflashcallback/#flash_hide_callback
     * @todo fix, somehow not really working on test.html
     */
    interface FlashInfo {
        state: string;
    }

    /**
     * https://developers.facebook.com/docs/reference/javascript/
     */
    export interface Facebook {

        Event: Event;
        XFBML: XFBML;
        Canvas: Canvas;

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.api/
         * @todo huge thing to implement
         */
            api: {};

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.init/
         */
            init: {
            (options: InitOptions): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.ui/
         * @todo add login dialog
         * @todo check redirect_uri param
         */
            ui: {
            (params: FeedDialogParams, callback: (response: FeedDialogResponse) => void): void;
            (params: RequestsDialogParams, callback: (response: RequestDialogResponse) => void): void;
            (params: SendDialogParams): void;
            (params: FriendsDialogParams, callback: (response: FriendsDialogResponse) => void): void;
            (params: PayDialogParams, callback: (response: PayDialogResponse) => void): void;
            (params: AddPageDialogParams): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.getAuthResponse/
         */
            getAuthResponse: {
            (): AuthResponse;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.getLoginStatus/
         */
            getLoginStatus: {
            (callback: (response: LoginStatusResponse) => void): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.login/
         */
            login: {
            (callback: (response: LoginStatusResponse) => void): void;
            (callback: (response: LoginStatusResponse) => void, options: LoginOptions): void;
        };

        /**
         * https://developers.facebook.com/docs/reference/javascript/FB.logout/
         */
            logout: {
            (callback: (response: LoginStatusResponse) => void): void;
        };
    }
}

declare var FB: Facebook.Facebook;