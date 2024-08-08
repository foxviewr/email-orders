import PageTitle from "@/components/PageTitle";

export default function NotFoundPage() {
    return (
        <div className="flex flex-grow sm:justify-center items-center sm:pt-0">
            <div className="w-full sm:max-w-md px-6 py-4 text-center">
                <PageTitle>404 Page not found!</PageTitle>
                <div className="mt-6">
                    <p>We can't find what you're looking for :(</p>
                </div>
            </div>
        </div>
    )
}
