using DCA.Model;
using DCA.Model.Contracts;

namespace DCA.Exchanges.Bitstamp;

public class Converter : IConverter<Euro>, IConverter<Dollar>
{
    public Bitcoin Convert(Euro amount)
    {
        return new Bitcoin();
    }

    public Bitcoin Convert(Dollar amount)
    {
        return new Bitcoin();
    }
}
