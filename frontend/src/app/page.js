import PageTitle from "@/components/PageTitle"

export default function Home() {
    return (
        <div className="w-full">
            <PageTitle>Welcome!</PageTitle>
            <div className="mt-6">
                <p><strong>Email Orders</strong> is a email-based ordering platform.</p>
                <br/>
                <p>Start by creating or logging into an account so you can view all your orders. Inside each other you
                    can view all the email conversations and reply to it.</p>
                <br/>
                <p>Any customer email sent to the configured email address will either answer to an existing order or
                    create a new one.</p>
            </div>
        </div>
    )
}
